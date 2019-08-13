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
            $sql->execute(array(':startDate' => $newDate, ':status' => 'new'));
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
}
