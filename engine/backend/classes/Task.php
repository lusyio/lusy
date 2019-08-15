<?php

class Task
{
    private $taskData;
    public $hasEditAccess = false;
    private $companyUsers = [];

    public function __construct($taskId, $taskData = null, $subTaskFilterString = '')
    {
        require_once __ROOT__ . '/engine/backend/classes/TaskList.php';
        require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
        global $id;
        global $pdo;
        global $roleu;

        if (is_null($taskData)) {
            $taskQuery = $pdo->prepare("SELECT t.id, t.name, t.status, t.description, t.author, t.manager, t.worker, 
          t.idcompany, t.view, t.datecreate, t.datedone, t.report, t.view_status, t.parent_task, t.checklist, t.with_premium,
          (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countComments,
          (SELECT COUNT(*) FROM events e WHERE e.action='comment' AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewComments,
          (SELECT COUNT(DISTINCT u.file_id) FROM uploads u LEFT JOIN events e ON u.comment_id = e.comment WHERE u.comment_type='comment' AND (e.action='comment' OR e.action='review') AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewFiles,
          (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
          (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
          FROM tasks t WHERE t.id = :taskId");
            $taskQuery->execute(array(':taskId' => $taskId, ':userId' => $id));
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
        if ($isCeo || $this->get('manager') == $id) {
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

        $this->setCompanyUsers();
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

    private function setCompanyUsers()
    {
        global $pdo;
        global $idc;

        $usersQuery = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND is_fired = 0");
        $usersQuery->execute([':companyId' => $idc]);
        $this->companyUsers = $usersQuery->fetchAll(PDO::FETCH_COLUMN);
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

        if ($this->get('status') == 'planned' && $newDate != $this->get('datecreate')) {
            if ($newDate <= time() && $newDate >= strtotime('midnight')) {
                $sql->execute(array(':taskId' => $this->get('id'), ':startDate' => $newDate, ':status' => 'new'));
                resetViewStatus($this->get('id'));
                addTaskCreateComments($this->get('id'), $this->get('worker'), $this->get('coworkers'));
                addEvent('createtask', $this->get('id'), $this->get('datedone'), $this->get('worker'));
            } else {
                $sql->execute([':taskId' => $this->get('id'), ':startDate' => $newDate, ':status' => 'planned']);
            }
            return true;
        }
        return false;
    }

    function changeCoworkers($newCoworkers)
    {
        global $pdo;
        $isChanged = false;
        $addCoworkerQuery = $pdo->prepare("INSERT INTO task_coworkers SET task_id =:taskId, worker_id = :coworkerId");
        foreach ($newCoworkers as $newCoworker) {
            if (!in_array($newCoworker, $this->get('coworkers')) && in_array($newCoworker, $this->companyUsers)) { //добавляем соисполнителя, если его еще нет в таблице
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
        $isChanged = false;
        if ($newWorker != $this->get('worker') && in_array($newWorker, $this->companyUsers)) {
            $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
            $changeWorkerQuery->execute([':taskId' => $this->get('id'), ':newWorker' => $newWorker]);
            addChangeExecutorsComments($this->get('id'), 'newworker', $newWorker);
            if ($this->get('status') != 'planned') {
                addEvent('changeworker', $this->get('id'), '', $this->get('worker'));
                $isChanged = true;
            }
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

    public function checkRowInCheckList($checkListRow, $userId)
    {
        global $pdo;
        $checkList = $this->getCheckList();
        if ($checkList[$checkListRow]['status'] == 0) {
            $checkList[$checkListRow]['status'] = 1;
            $checkList[$checkListRow]['checkedBy'] = $userId;
            $checkList[$checkListRow]['checkTime'] = time();
        } elseif ($userId == $this->hasEditAccess || ($checkList[$checkListRow]['checkedBy'] == $userId && $checkList[$checkListRow]['checkTime'] > time() - 300)) {
            $checkList[$checkListRow]['status'] = 0;
            $checkList[$checkListRow]['checkTime'] = 0;
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

    public static function createTask($name, $description, $dateCreate, $manager, $worker, $coworkers, $dateDone, $checkList, $parentTaskId, $taskPremiumType)
    {
        global $pdo;
        global $id;
        global $idc;
        global $roleu;

        $companyUsersQuery = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND is_fired = 0");
        $companyUsersQuery->execute([':companyId' => $idc]);
        $companyUsers = $companyUsersQuery->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array($manager, $companyUsers) || !in_array($worker, $companyUsers)) {
            return false;
        }
        foreach ($coworkers as $coworkerId) {
            if (!in_array($coworkerId, $companyUsers)) {
                return false;
            }
        }

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
            ':checkList' => json_encode($checkList),
            ':parentTask' => null,
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
        if (count($checkList) > 0) {
            $usePremiumTask = true;
        }
        if ($dateCreate > time() && $dateCreate <= $dateDone && ($taskPremiumType >= 0)) {
            $taskCreateQueryData[':status'] = 'planned';
            $usePremiumTask = true;
        }
        $taskCreateQuery = $pdo->prepare("INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view, parent_task, with_premium) VALUES (:name, :description, :dateCreate, :datedone, NULL, :status, :author, :manager, :worker, :companyId, :description, '0', :parentTask, :withPremium)");
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

    public static function createSanitizedCheckList($jsonCheckListString)
    {
        $checklist = [];
        $unsafeChecklist = json_decode($jsonCheckListString, true);
        foreach ($unsafeChecklist as $key => $value) {
            $checklist[$key]['text'] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            $checklist[$key]['status'] = 0;
            $checklist[$key]['checkedBy'] = 0;
        }
        return $checklist;
    }

    public function updateCheckList($checkList)
    {
        global $pdo;
        //сравнить старый и новый чеклисты
        $isCheckListChanged = false;
        $oldCheckList = json_decode($this->get('checklist'), true);
        if (!$oldCheckList) {
            $oldCheckList = [];
        }
        if (count($oldCheckList) != count($checkList)) {
            $isCheckListChanged = true;
        } else {
            foreach ($checkList as $key => $value) {
                if ($checkList[$key]['text'] != $oldCheckList[$key]['text']) {
                    $isCheckListChanged = true;
                    break;
                }
            }
        }
        if ($isCheckListChanged) {
            $checkListJson = json_encode($checkList);
            $updateCheckListQuery = $pdo->prepare("UPDATE tasks SET checklist = :checkList WHERE id = :taskId");
            $updateCheckListQuery->execute([':checkList' => $checkListJson, ':taskId' => $this->get('id')]);
        }
        return $isCheckListChanged;
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

    public function updateTaskNameAndDescription($newName, $newDescription)
    {
        global $pdo;
        if (($newName != $this->get('name')) || ($newDescription != $this->get('description'))) {
            $updateNameAndDescriptionQuery = $pdo->prepare("UPDATE tasks SET name = :name, description = :description WHERE id = :taskId");
            $updateNameAndDescriptionQuery->execute([':taskId' => $this->get('id'), ':name' => $newName, ':description' => $newDescription]);
            return true;
        }
        return false;
    }

    public function updateDatedone($newDatedone)
    {
        if ($newDatedone != $this->get('datedone') && $newDatedone >= strtotime('midnight')) {
            $this->sendDate($newDatedone);
            return true;
        }
        return false;
    }

    public function addParentTask($parentTaskId)
    {
        global $idc;
        global $pdo;
        $parentTask = new Task($parentTaskId);
        if ($parentTask->get('idcompany') == $idc && $parentTaskId != $this->get('parent_task') && is_null($parentTask->get('parent_task')) && count($this->get('subTasks')) == 0) {
            $addParentTaskQuery = $pdo->prepare("UPDATE tasks SET parent_task = :parentTaskId WHERE id = :taskId");
            $addParentTaskQuery->execute([':taskId' => $this->get('id'), ':parentTaskId' => $parentTaskId]);
            addSubTaskComment($parentTaskId, $this->get('id'));
            addNewSubTaskEvent($parentTaskId, $this->get('id'));
            return true;
        }
        return false;
    }

    public function removeParentTask()
    {
        global $pdo;
        if (!is_null($this->get('parent_task'))) {
            $addParentTaskQuery = $pdo->prepare("UPDATE tasks SET parent_task = NULL WHERE id = :taskId");
            $addParentTaskQuery->execute([':taskId' => $this->get('id')]);
            return true;
        }
        return false;
    }

    public static function getCompanyIdByTask($taskId)
    {
        global $pdo;
        $query = $pdo->prepare("SELECT idcompany FROM tasks WHERE id = :taskId");
        $query->execute([':taskId' => $taskId]);
        $companyId = $query->fetch(PDO::FETCH_COLUMN);
        return $companyId;
    }
}
