<?php
require_once 'engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;

$cometTrackChannelName = getCometTrackChannelName();

$otbor = '(worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ') and status!="done"';
$usedStatuses = DB('DISTINCT `status`', 'tasks', $otbor);
$sortedUsedStatuses = getSortedStatuses($usedStatuses);

$isManager = DBOnce('id', 'tasks', 'manager=' . $GLOBALS["id"]);
$isWorker = DBOnce('id', 'tasks', 'worker=' . $GLOBALS["id"]);

$tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.datepostpone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view,
       IF(t.datepostpone IS NULL OR t.datepostpone='0000-00-00', t.datedone, t.datepostpone) AS sort_date,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE (manager=:userId OR worker=:userId) AND t.status NOT IN ('done', 'canceled') ORDER BY sort_date";
$dbh = $pdo->prepare($tasksQuery);
$dbh->execute(array(':userId' => $id));
$tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
$countAllTasks = count($tasks);
prepareTasks($tasks);

