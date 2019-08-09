<?php

class Task
{
    private $taskData;

    public function __construct($taskId)
    {
        require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
        global $id;
        global $pdo;
        global $roleu;

        $taskQuery = $pdo->prepare('SELECT t.id, t.name, t.status, t.description, t.author, t.manager, t.worker, 
          t.idcompany, t.view, t.datecreate, t.datedone, t.report, t.view_status, t.parent_task, t.checklist, t.with_premium
          FROM tasks t WHERE t.id = :taskId');
        $taskQuery->execute(array(':taskId' => $taskId));
        $this->taskData = $taskQuery->fetch(PDO::FETCH_ASSOC);

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
            $subTasksQuery = $pdo->prepare("SELECT id FROM tasks WHERE parent_task = :taskId");
            $subTasksQuery->execute([':taskId' => $this->get('id')]);
        } else {
            $subTasksQuery = $pdo->prepare("SELECT DISTINCT t.id FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE t.parent_task = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)");
            $subTasksQuery->execute([':taskId' => $taskId, ':userId' => $id]);
        }
        $subTasks = $subTasksQuery->fetchAll(PDO::FETCH_COLUMN);
        foreach ($subTasks as $subtaskId) {
            $this->taskData['subTasks'][] = new Task($subtaskId);
        }

        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
        $filesQuery->execute(array(':commentId' => $taskId, ':commentType' => 'task'));
        $this->taskData['files'] = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
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
        $viewer = $pdo->prepare('UPDATE `tasks` SET status = "inwork" where id = :taskId');
        $viewer->execute([':taskId' => $this->get('id')]);
    }
}
