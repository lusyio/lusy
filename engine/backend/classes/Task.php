<?php

class Task
{
    private $taskData;
    public $hasEditAccess = false;

    public function __construct($taskId, $taskData = null, $subTaskFilterString = '')
    {
        require_once __ROOT__ . '/engine/backend/classes/TaskList.php';
        require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
        global $id;
        global $pdo;
        global $roleu;

        if (is_null($taskData)) {
            $taskQuery = $pdo->prepare('SELECT t.id, t.name, t.status, t.description, t.author, t.manager, t.worker, 
          t.idcompany, t.view, t.datecreate, t.datedone, t.report, t.view_status, t.parent_task, t.checklist, t.with_premium
          FROM tasks t WHERE t.id = :taskId');
            $taskQuery->execute(array(':taskId' => $taskId));
            $this->taskData = $taskQuery->fetch(PDO::FETCH_ASSOC);
        } else {
            $this->taskData = $taskData;
        }


        $coworkersQuery = $pdo->prepare("SELECT tc.worker_id FROM task_coworkers tc LEFT JOIN users u ON tc.worker_id = u.id WHERE tc.task_id = :taskId");
        $coworkersQuery->execute(array(':taskId' => $taskId));
        $this->taskData['coworkers'] = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN);

        $this->taskData['managerDisplayName'] = getDisplayUserName($this->taskData['manager']);
        $this->taskData['workerDisplayName'] = getDisplayUserName($this->taskData['worker']);
        $this->taskData['subTasks'] = [];
        if ($roleu == 'ceo') {
            $isCeo = true;
        } else {
            $isCeo = false;
        }
        if ($isCeo || $this->get('manager') == $id || $this->taskData['worker'] == $id || in_array($id, $this->taskData['coworkers'])) {
            $subTasksQueryString = "SELECT t.id FROM tasks t WHERE t.parent_task = :taskId";
            $subTasksQueryData = [
                ':taskId' => $this->get('id')
            ];
        } else {
            $subTasksQueryString = "SELECT DISTINCT t.id FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE t.parent_task = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)";
            $subTasksQueryData = [
                ':taskId' => $taskId,
                ':userId' => $id
            ];
        }
        if ($subTaskFilterString != '') {
            $subTasksQueryString .= $subTaskFilterString;
        }
        $subTasksQuery = $pdo->prepare($subTasksQueryString);
        $subTasksQuery->execute($subTasksQueryData);
        $subTasks = $subTasksQuery->fetchAll(PDO::FETCH_COLUMN);
        foreach ($subTasks as $subtaskId) {
            $this->taskData['subTasks'][] = new Task($subtaskId);
        }

        if ($isCeo || $this->get('manager') == $id) {
            $this->hasEditAccess = true;
        }

        $this->taskData['viewStatus'] = json_decode($this->taskData['view_status'], true);
        if (isset($this->taskData['viewStatus'][$id])) {
            $this->taskData['viewStatus'] = true;
        } else {
            $this->taskData['viewStatus'] = false;
        }

        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
        $filesQuery->execute(array(':commentId' => $taskId, ':commentType' => 'task'));
        $this->taskData['files'] = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        if ($isCeo && $id != $this->taskData['manager'] && $id != $this->taskData['worker'] && !in_array($id, $this->taskData['coworkers'])) {
            $this->taskData['mainRole'] = 'ceo';
        } else if ($id == $this->taskData['manager']) {
            $this->taskData['mainRole'] = 'manager';
        } else {
            $this->taskData['mainRole'] = 'worker';
        }

        if (is_array($this->taskData['subTasks'])) {
            usort($this->taskData['subTasks'], ['TaskList', 'compareWithoutSubTasks']);
        }
    }

    public function setQueryStatusFilter($status, $in = true)
    {
        $appendix = ' AND t.status';
        if ($in) {
            $appendix .= ' IN ';
        } else {
            $appendix .= ' NOT IN ';
        }
        if (is_array($status)) {
            $appendix .= "('" . implode("', '", $status) . "')";
        } else {
            $appendix .= "('" . $status . "')";
        }
        $this->query .= $appendix;
    }

    public function get($param)
    {
        if (isset($this->taskData[$param])) {
            return $this->taskData[$param];
        }
        return null;
    }

    function getDateProgress()
    {
        $createDate = $this->get('datecreate');
        $finishDate = $this->get('datedone');
        $dateCreateDateDoneDiff = $finishDate - $createDate;
        if ($finishDate > time()) {
            $daysTotal = $dateCreateDateDoneDiff / (60 * 60 * 24) + 1;
            $daysPassed = ceil((time() - $createDate) / (60 * 60 * 24));
            return round(($daysPassed) * 100 / $daysTotal);
        } else {
            return 100;
        }
    }

    function getCheckList()
    {
        $checklist = json_decode($this->get('checklist'), true);
        if (is_null($checklist)) {
            $checklist = [];
        } else {
            foreach ($checklist as $key => $value) {
                if ($value['status'] == 1) {
                    $checklist[$key]['name'] = getDisplayUserName($value['checkedBy']);
                }
            }
        }
        return $checklist;
    }

    function markTaskEventsAsRead()
    {
        global $id;
        global $pdo;

        $updateEventsQuery = $pdo->prepare("UPDATE events SET view_status = 1 WHERE task_id = :taskId AND recipient_id = :userId AND view_status=0");
        $updateEventsQuery->execute(array(':taskId' => $this->get('id'), ':userId' => $id));
    }

    function markTaskAsRead()
    {
        global $id;
        global $pdo;
        require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

        $setViewedQuery = $pdo->prepare('UPDATE `tasks` SET view = :viewState where id = :taskId');
        $setViewedQuery->execute(array('viewState' => 1, ':taskId' => $this->get('id')));
        $isOldTaskQuery = $pdo->prepare("SELECT COUNT(*) FROM events WHERE task_id = :taskId and action='viewtask'");
        $isOldTaskQuery->execute([':taskId' => $this->get('id')]);
        $isOldTask = (boolean)$isOldTaskQuery->fetch(PDO::FETCH_COLUMN);
        if (!$isOldTask) {
            addEvent('viewtask', $this->get('id'), '', $this->get('manager'));
        }
    }

    function getSubTasks($isForCeo)
    {
        global $id;
        global $pdo;
        if ($isForCeo || $this->get('manager') == $id || $this->get('worker') == $id || in_array($id, $this->get('coworkers'))) {
            $subTasksQuery = $pdo->prepare("SELECT id FROM tasks WHERE parent_task = :taskId");
            $subTasksQuery->execute([':taskId' => $this->get('id')]);
        } else {
            $subTasksQuery = $pdo->prepare("SELECT DISTINCT t.id FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE t.parent_task = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)");
            $subTasksQuery->execute([':taskId' => $this->get('id'), ':userId' => $id]);
        }
        $subtasks = $subTasksQuery->fetchAll(PDO::FETCH_COLUMN);
        $result = [];
        foreach ($subtasks as $subtaskId) {
            $result[] = new Task($subtaskId);
        }
        return $result;
    }

    function updateTaskStatus($newStatus)
    {
        if ($newStatus != 'inwork') {
            return;
        }
        global $pdo;
        $viewer = $pdo->prepare("UPDATE `tasks` SET status = 'inwork' where id = :taskId");
        $viewer->execute([':taskId' => $this->get('id')]);
    }

    function sendDate($newDate)
    {
        global $pdo;
        $sql = $pdo->prepare("UPDATE `tasks` SET `status` = :status, datedone = :newDate, `view` = 0 WHERE id = :taskId");

        if ($this->get('status') != 'planned') {
            $sql->execute(array(':taskId' => $this->get('id'), ':newDate' => $newDate, ':status' => 'inwork'));
            addChangeDateComments($this->get('id'), 'senddate', $newDate);
            resetViewStatus($this->get('id'));
            addEvent('senddate', $this->get('id'), $newDate);
        } elseif ($newDate > strtotime('midnight')) {
            $sql->execute([':taskId' => $this->get('id'), ':newDate' => $newDate, ':status' => 'planned']);
        }
    }

    function changeStartDate($newDate)
    {
        global $pdo;
        $sql = $pdo->prepare("UPDATE `tasks` SET `status` = :status, datecreate = :startDate, `view` = 0 WHERE id = :taskId");

        if ($newDate <= time()) {
            $sql->execute(array(':taskId' => $this->get('id'), ':startDate' => $newDate, ':status' => 'new'));
            resetViewStatus($this->get('id'));
            addTaskCreateComments($this->get('id'), $this->get('worker'), $this->get('coworkers'));
            addEvent('createtask', $this->get('id'), $this->get('datedone'), $this->get('worker'));
        } else {
            $sql->execute([':taskId' => $this->get('id'), ':startDate' => $newDate, ':status' => 'planned']);
        }
    }

    function changeCoworkers($newCoworkers)
    {
        global $pdo;
        $isChanged = false;
        $addCoworkerQuery = $pdo->prepare("INSERT INTO task_coworkers SET task_id =:taskId, worker_id=:coworkerId");
        foreach ($newCoworkers as $newCoworker) {
            if (!in_array($newCoworker, $this->get('coworkers'))) { //добавляем соисполнителя, если его еще нет в таблице
                $addCoworkerQuery->execute([':taskId' => $this->get('id'), ':coworkerId' => $newCoworker]);
                if ($this->get('status') != 'planned') {
                    addChangeExecutorsComments($this->get('id'), 'addcoworker', $newCoworker);
                    addEvent('addcoworker', $this->get('id'), '', $newCoworker);
                    $isChanged = true;
                }
            }
        }
        $deleteCoworkerQuery = $pdo->prepare('DELETE FROM task_coworkers where task_id = :taskId AND worker_id = :coworkerId');
        foreach ($this->get('coworkers') as $oldCoworker) {
            if (!in_array($oldCoworker, $newCoworkers)) { // удаляем соисполнителя, если его нет в новом списке соисполнителей
                $deleteCoworkerQuery->execute([':taskId' => $this->get('id'), ':coworkerId' => $oldCoworker]);
                if ($this->get('status') != 'planned') {
                    addChangeExecutorsComments($this->get('id'), 'removecoworker', $oldCoworker);
                    addEvent('removecoworker', $this->get('id'), '', $oldCoworker);
                    $isChanged = true;
                }
            }
        }
        return $isChanged;
    }

    function changeWorker($newWorker)
    {
        global $pdo;
        $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
        $changeWorkerQuery->execute([':taskId' => $this->get('id'), ':newWorker' => $newWorker]);
        addChangeExecutorsComments($this->get('id'), 'newworker', $newWorker);
        if ($this->get('status') != 'planned') {
            addEvent('changeworker', $this->get('id'), '', $this->get('worker'));
            $isChanged = true;
        }
        return $isChanged;
    }

    function sendOnReview($reportText, $files, $premiumType)
    {
        $usePremiumCloud = false;

        setStatus($this->get('id'), 'pending');
        $commentId = addSendOnReviewComments($this->get('id'), $reportText);

        $this->attachDeviceFilesToComment($commentId);
        $this->attachCloudFilesToComment($files, $commentId, $premiumType);

        resetViewStatus($this->get('id'));
        addEvent('review', $this->get('id'), $commentId);
    }

    function workReturn($datePostpone, $reportText, $files, $premiumType)
    {
        setStatus($this->get('id'), 'returned', $datePostpone);
        $commentId = addWorkReturnComments($this->get('id'), $datePostpone, $reportText);

        $this->attachDeviceFilesToComment($commentId);
        $this->attachCloudFilesToComment($files, $commentId, $premiumType);

        resetViewStatus($this->get('id'));
        addEvent('workreturn', $this->get('id'), $commentId);
    }

    public function checkSubTasksForFinish()
    {
        $subTasks = $this->get('subTasks');
        $result = [
            'status' => true,
            'tasks' => [],
        ];
        foreach ($subTasks as $subTask) {
            if (!in_array($subTask->get('status'), ['canceled', 'done'])) {
                $result['status'] = false;
                $result['tasks'][] = [
                    'id' => $subTask['id'],
                    'name' => $subTask['name'],
                ];
            }
        }
        return $result;
    }

    public function workDone()
    {
        setFinalStatus($this->get('id'), 'done');
        addFinalComments($this->get('id'), 'done');
        resetViewStatus($this->get('id'));
        if ($this->get('status') != 'planned') {
            addEvent('workdone', $this->get('id'), '');
        }
    }

    public function cancelTask()
    {
        setFinalStatus($this->get('id'), 'canceled');
        addFinalComments($this->get('id'), 'canceled');
        resetViewStatus($this->get('id'));
        if ($this->get('status') != 'planned') {
            addEvent('canceltask', $this->get('id'), '');
        }
    }

    public function sendPostpone($datePostpone, $text)
    {
        setStatus($this->get('id'), 'postpone');
        addPostponeComments($this->get('id'), $datePostpone, $text);
        resetViewStatus($this->get('id'));
        addEvent('postpone', $this->get('id'), $datePostpone, $this->get('manager'));
    }

    public function confirmDate()
    {
        $statusWithDate = DBOnce('status', 'comments', "idtask=" . $this->get('id') . " and status like 'request%' order by `datetime` desc");
        $datePostpone = preg_split('~:~', $statusWithDate)[1];
        if ($datePostpone == $this->get('datedone')) {
            exit;
        }
        setStatus($this->get('id'), 'inwork', $datePostpone);

        addChangeDateComments($this->get('id'), 'confirmdate', $datePostpone);
        resetViewStatus($this->get('id'));
        addEvent('confirmdate', $this->get('id'), $datePostpone);
    }

    public function cancelDate()
    {
        $this->updateTaskStatus('inwork');
        addChangeDateComments($this->get('id'), 'canceldate');
        resetViewStatus($this->get('id'));
        addEvent('canceldate', $this->get('id'), $this->get('datedone'));
    }

    public function updateChecklist($checkListRow, $userId)
    {
        global $pdo;
        $checkList = $this->getCheckList();
        if ($checkList[$checkListRow]['status'] == 0) {
            $checkList[$checkListRow]['status'] = 1;
            $checkList[$checkListRow]['checkedBy'] = $userId;
            $checkList[$checkListRow]['checkTime'] = time();
        } elseif ($userId == $this->hasEditAccess || ($checkList[$checkListRow]['checkedBy'] == $userId && $checkList[$checkListRow]['checkTime'] > time() - 300)) {
            $checklist[$checkListRow]['status'] = 0;
            $checklist[$checkListRow]['checkTime'] = 0;
        } else {
            return -1;
        }
        $updateCheckListQuery = $pdo->prepare('UPDATE `tasks` SET checklist = :checklist WHERE id= :taskId');
        $updateCheckListData = [
            ':checklist' => json_encode($checkList),
            ':taskId' => $this->get('id'),
        ];
        $updateStatus = $updateCheckListQuery->execute($updateCheckListData);
        if ($updateStatus) {
            return $checkList[$checkListRow]['status'];
        } else {
            return -1;
        }
    }

    public static function createTask($name, $description, $dateCreate, $manager, $worker, $coworkers, $dateDone, $parentTaskId, $checklist, $taskPremiumType)
    {
        global $pdo;
        global $id;
        global $idc;
        global $roleu;

        $usePremiumTask = false;
        $taskCreateQueryData = [
            ':name' => $name,
            ':description' => $description,
            ':dateCreate' => $dateCreate,
            ':author' => $id,
            ':manager' => $manager,
            ':worker' => $worker,
            ':companyId' => $idc,
            ':datedone' => $dateDone,
            ':status' => 'new',
            ':parentTask' => null,
            ':checklist' => json_encode($checklist),
            ':withPremium' => 0,
        ];

        if ($parentTaskId != 0 && ($taskPremiumType >= 0)) {
            $parentTask = new Task($parentTaskId);
            if (in_array($id, [$parentTask->get('manager'), $parentTask->get('worker')]) || ($roleu == 'ceo' && $parentTask->get('idcompany') == $idc)) {
                if (is_null($parentTask->get('parent_task'))) {
                    $taskCreateQueryData[':parentTask'] = $parentTask->get('id');
                    $taskCreateQueryData[':withPremium'] = 1;
                    $usePremiumTask = true;
                }
            }
        }
        if ($dateCreate > time() && $dateCreate <= $dateDone && ($taskPremiumType >= 0)) {
            $taskCreateQueryData[':status'] = 'planned';
            $usePremiumTask = true;
        }
        $taskCreateQuery = $pdo->prepare("INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view, parent_task, checklist, with_premium) VALUES (:name, :description, :dateCreate, :datedone, NULL, :status, :author, :manager, :worker, :companyId, :description, '0', :parentTask, :checklist, :withPremium)");
        $taskCreateQuery->execute($taskCreateQueryData);
        if ($taskCreateQuery) {
            $taskId = $pdo->lastInsertId();
            if (!empty($taskId)) {
                $coworkersQuery = "INSERT INTO task_coworkers(task_id, worker_id) VALUES (:taskId, :workerId)";
                $sql = $pdo->prepare($coworkersQuery);
                foreach ($coworkers as $workerId) {
                    $sql->execute(array(':taskId' => $taskId, ':workerId' => $workerId));
                }
            }
            if ($taskCreateQueryData[':status'] != 'planned') {
                resetViewStatus($taskId);
                addTaskCreateComments($taskId, $worker, $coworkers);
                addEvent('createtask', $taskId, $dateDone, $worker);
            } else {
                addEvent('createplantask', $taskId, $dateCreate, $worker);
            }
            if (!is_null($taskCreateQueryData[':parentTask'])) {
                addSubTaskComment($taskCreateQueryData[':parentTask'], $taskId);
                addNewSubTaskEvent($taskCreateQueryData[':parentTask'], $taskId);
            }

            if ($taskPremiumType == 0 && $usePremiumTask) {
                updateFreePremiumLimits($idc, 'task');
            }
            return new Task($taskId);
        } else {
            return false;
        }
    }

    public function attachCloudFilesToTask($files, $cloudPremiumType)
    {
        $usePremiumCloud = false;
        if (count($files['google']) > 0 && ($cloudPremiumType >= 0)) {
            addGoogleFiles('task', $this->get('id'), $files['google']);
            $usePremiumCloud = true;
        }
        if (count($files['dropbox']) > 0 && ($cloudPremiumType >= 0)) {
            addDropboxFiles('task', $this->get('id'), $files['dropbox']);
            $usePremiumCloud = true;
        }
        if ($cloudPremiumType == 0 && $usePremiumCloud) {
            updateFreePremiumLimits($this->get('idcompany'), 'cloud');
        }
    }

    public function attachDeviceFilesToTask()
    {
        if (count($_FILES) > 0) {
            uploadAttachedFiles('task', $this->get('id'));
        }
    }

    private function attachCloudFilesToComment($files, $commentId, $cloudPremiumType)
    {
        $usePremiumCloud = false;
        if (count($files['google']) > 0 && ($cloudPremiumType >= 0)) {
            addGoogleFiles('comment', $commentId, $files['google']);
            $usePremiumCloud = true;
        }
        if (count($files['dropbox']) > 0 && ($cloudPremiumType >= 0)) {
            addDropboxFiles('comment', $commentId, $files['dropbox']);
            $usePremiumCloud = true;
        }
        if ($cloudPremiumType == 0 && $usePremiumCloud) {
            updateFreePremiumLimits($this->get('idcompany'), 'cloud');
        }
    }

    private function attachDeviceFilesToComment($commentId)
    {
        if (count($_FILES) > 0) {
            uploadAttachedFiles('task', $commentId);
        }
    }
}
