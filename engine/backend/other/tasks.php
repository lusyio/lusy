<?php
require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $supportCometHash;
global $roleu;
global $now;
global $_months;
$cometTrackChannelName = getCometTrackChannelName();

$otbor = '(worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ') and status!="done"';
$usedStatuses = DB('DISTINCT `status`', 'tasks', $otbor);
$sortedUsedStatuses = getSortedStatuses($usedStatuses);

$isManager = DBOnce('id', 'tasks', 'manager=' . $GLOBALS["id"]);
$isWorker = DBOnce('id', 'tasks', 'worker=' . $GLOBALS["id"]);
$ceoTasksQuery = "SELECT DISTINCT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view, t.parent_task, LOCATE( :quotedUserId, t.view_status) AS view_order,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT COUNT(*) FROM events e WHERE e.action='comment' AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewComments,
       (SELECT COUNT(DISTINCT u.file_id) FROM uploads u LEFT JOIN events e ON u.comment_id = e.comment WHERE u.comment_type='comment' AND (e.action='comment' OR e.action='review') AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewFiles,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE t.idcompany = :companyId AND t.status NOT IN ('done', 'canceled') ORDER BY FIELD(status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, datedone";

$tasksQuery = "SELECT DISTINCT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view, t.parent_task, LOCATE( :quotedUserId, t.view_status) AS view_order,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT COUNT(*) FROM events e WHERE e.action='comment' AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewComments,
       (SELECT COUNT(DISTINCT u.file_id) FROM uploads u LEFT JOIN events e ON u.comment_id = e.comment WHERE u.comment_type='comment' AND (e.action='comment' OR e.action='review') AND e.task_id = t.id AND recipient_id = :userId AND e.view_status = 0) AS countNewFiles,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t 
LEFT JOIN task_coworkers tc ON tc.task_id = t.id
WHERE (t.id IN (SELECT DISTINCT t.parent_task FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE (manager=:userId OR worker=:userId OR tc.worker_id=:userId) AND t.status NOT IN ('done', 'canceled'))
OR (t.manager=:userId OR t.worker=:userId OR tc.worker_id=:userId)) AND t.status NOT IN ('done', 'canceled') AND (t.status <> 'planned' OR t.manager = :userId) ORDER BY FIELD(status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, datedone";


require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
$taskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');

$taskList->setQueryStatusFilter(['done', 'canceled'], false);
$taskList->setSubTaskFilterString(['done', 'canceled'], false);
$taskList->setParentTaskNullFilterString(false);
$taskList->executeQuery();
$taskList->instantiateTasks();
$taskList->sortActualTasks();
$tasks = $taskList->getTasks();
$countAllTasks = $taskList->countTasks();


$archiveTasksQuery = $pdo->prepare("SELECT COUNT(DISTINCT t.id) AS count, t.status FROM tasks t LEFT JOIN task_coworkers tc ON tc.task_id = t.id WHERE (t.worker= :userId OR t.manager = :userId OR tc.worker_id = :userId) AND t.status IN ('done', 'canceled') GROUP BY t.status");
$archiveTasksQuery->execute([':userId' => $id]);
$archiveTasksCount = $archiveTasksQuery->fetchAll(PDO::FETCH_ASSOC);
$countArchiveDoneTasks = 0;
$countArchiveCanceledTasks = 0;
foreach ($archiveTasksCount as $group) {
    if ($group['status'] == 'done') {
        $countArchiveDoneTasks = $group['count'];
    } elseif ($group['status'] == 'canceled') {
        $countArchiveCanceledTasks = $group['count'];
    }
}
