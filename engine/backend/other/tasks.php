<?php
require_once 'engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $roleu;
global $now;
$cometTrackChannelName = getCometTrackChannelName();

$otbor = '(worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ') and status!="done"';
$usedStatuses = DB('DISTINCT `status`', 'tasks', $otbor);
$sortedUsedStatuses = getSortedStatuses($usedStatuses);

$isManager = DBOnce('id', 'tasks', 'manager=' . $GLOBALS["id"]);
$isWorker = DBOnce('id', 'tasks', 'worker=' . $GLOBALS["id"]);
$ceoTasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view, LOCATE( :quotedUserId, t.view_status) AS view_order,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE t.idcompany = :companyId AND t.status NOT IN ('done', 'canceled') ORDER BY FIELD(status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, datedone";

$tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view, LOCATE( :quotedUserId, t.view_status) AS view_order,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t 
LEFT JOIN task_coworkers tc ON tc.task_id = t.id
WHERE (manager=:userId OR worker=:userId OR tc.worker_id=:userId) AND t.status NOT IN ('done', 'canceled') ORDER BY FIELD(status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, datedone";

if ($roleu == 'ceo') {
    $dbh = $pdo->prepare($ceoTasksQuery);
    $dbh->execute(array(':companyId' => $idc, ':quotedUserId' => '"' . $id . '"'));
} else {
    $dbh = $pdo->prepare($tasksQuery);
    $dbh->execute(array(':userId' => $id, ':quotedUserId' => '"' . $id . '"'));
}

$tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
$countAllTasks = count($tasks);
$countArchiveDoneTasks = DBOnce('COUNT(*)', 'tasks t LEFT JOIN task_coworkers tc ON tc.task_id=t.id', "(t.worker='". $id ."' OR t.manager = '". $id . "' OR tc.worker_id = '". $id ."') AND t.status = 'done'");
$countArchiveCanceledTasks = DBOnce('COUNT(*)', 'tasks t LEFT JOIN task_coworkers tc ON tc.task_id=t.id', "(t.worker='". $id ."' OR t.manager = '". $id . "' OR tc.worker_id = '". $id ."') AND t.status = 'canceled'");
prepareTasks($tasks);

