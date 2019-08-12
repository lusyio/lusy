<?php

require_once __ROOT__ . '/engine/backend/classes/TaskList.php';

class CeoTaskList extends TaskList
{
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
                    WHERE t.idcompany = :companyId";
        $this->queryArgs = [
            ':userId' => $userId,
            ':companyId' => $companyId
        ];
    }

    public function executeQuery()
    {
        global $pdo;
        $tasksStmt = $pdo->prepare($this->query . $this->queryStatusFilterString . $this->parentTaskNullFilterString . $this->tasksQueryOrderString . $this->tasksQueryLimitString . $this->tasksQueryOffsetString);
        $tasksStmt->execute($this->queryArgs);
        $this->tasksQueryResult = $tasksStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
