<?php

require_once __ROOT__ . '/engine/backend/classes/TaskList.php';

class EmployeeTaskList extends TaskList
{
    private $parentTaskQuery;
    private $parentTaskQueryArgs;
    private $parentTaskFilterString = '';

    function __construct($userId, $companyId)
    {
        parent::__construct();
        $this->query = "SELECT DISTINCT t.id,
                   t.view_status, t.name, t.description, t.datecreate, t.datedone, t.status, t.manager, t.worker, t.idcompany, t.report, t.view, t.parent_task,
                   (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countComments,
                   (SELECT COUNT(*) FROM events e WHERE e.action='comment' AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewComments,
                   (SELECT COUNT(DISTINCT u.file_id) FROM uploads u LEFT JOIN events e ON u.comment_id = e.comment WHERE u.comment_type='comment' AND (e.action='comment' OR e.action='review') AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewFiles,
                   (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
                   (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
                    FROM tasks t 
                    LEFT JOIN task_coworkers tc ON tc.task_id = t.id
                    WHERE (t.id IN (:parentTasks) OR (t.manager=:userId OR t.worker=:userId OR tc.worker_id=:userId)) AND (t.status <> 'planned' OR t.manager = :userId)";
        $this->queryArgs = [
            ':userId' => $userId,
            'parentTasks' => ''
        ];

        $this->parentTaskQuery = "SELECT DISTINCT t.parent_task FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE (manager=:userId OR worker=:userId OR tc.worker_id=:userId)";
        $this->parentTaskQueryArgs = [
            ':userId' => $userId,
        ];

        $this->countQuery = "SELECT COUNT(DISTINCT t.id) FROM tasks t 
                    LEFT JOIN task_coworkers tc ON tc.task_id = t.id
                    WHERE (t.id IN (:parentTasks) OR (t.manager=:userId OR t.worker=:userId OR tc.worker_id=:userId)) AND (t.status <> 'planned' OR t.manager = :userId)";

    }

    public function executeQuery()
    {
        global $pdo;
        $parentTaskStmt = $pdo->prepare($this->parentTaskQuery . $this->parentTaskNullFilterString);
        $parentTaskStmt->execute($this->parentTaskQueryArgs);
        $parentTasks = $parentTaskStmt->fetchAll(PDO::FETCH_COLUMN);
        if (count($parentTasks) == 0) {
            $parentTasksString = 0;
        } else {
            $parentTasksString = implode(', ', $parentTasks);
        }
        $this->queryArgs['parentTasks'] = $parentTasksString;
        $tasksStmt = $pdo->prepare($this->query . $this->queryStatusFilterString . $this->tasksQueryOrderString . $this->tasksQueryLimitString . $this->tasksQueryOffsetString);
        $tasksStmt->execute($this->queryArgs);
        $tasksResult = $tasksStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tasksResult as $taskData) {
            $this->tasks[] = new Task($taskData['id'], $taskData, $this->subTaskFilterString);
        }
    }
    public function executeCountQuery()
    {
        global $pdo;
        $parentTaskStmt = $pdo->prepare($this->parentTaskQuery . $this->parentTaskNullFilterString);
        $parentTaskStmt->execute($this->parentTaskQueryArgs);
        $parentTasks = $parentTaskStmt->fetchAll(PDO::FETCH_COLUMN);
        if (count($parentTasks) == 0) {
            $parentTasksString = 0;
        } else {
            $parentTasksString = implode(', ', $parentTasks);
        }
        $this->queryArgs['parentTasks'] = $parentTasksString;
        $tasksStmt = $pdo->prepare($this->countQuery . $this->queryStatusFilterString);
        $tasksStmt->execute($this->queryArgs);
        $this->countResult = $tasksStmt->fetch(PDO::FETCH_COLUMN);
    }
}
