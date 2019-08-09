<?php
global $id;
global $idc;
global $roleu;
global $pdo;
global $datetime;
global $cometHash;
global $cometTrackChannelName;
global $_months;
global $tariff;
global $supportCometHash;

require_once __ROOT__ . '/engine/backend/functions/log-functions.php';
require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';
require_once __ROOT__ . '/engine/backend/classes/Task.php';

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}
$id_task = filter_var($_GET['task'], FILTER_SANITIZE_NUMBER_INT);

$taskClass = new Task($id_task);

$author = $taskClass->get('author');
$manager = $taskClass->get('manager');
$worker = $taskClass->get('worker');
$managerName = $taskClass->get('managerDisplayName');
$workerName = $taskClass->get('workerDisplayName');
$coworkersId = $taskClass->get('coworkers');
$files = $taskClass->get('files');

$subTasks = $taskClass->get('subTasks');
$unfinishedSubTasks = [];
foreach ($subTasks as $subTask) {
    if (!in_array($subTask->get('status'), ['done', 'canceled', 'planned'])) {
        $unfinishedSubTasks[] = $subTask->get('id');
    }
}
unset($subTask);
$hasUnfinishedSubTask = (bool)count($unfinishedSubTasks);

$hasOwnSubTaskQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE parent_task = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)");
$hasOwnSubTaskQuery->execute([':taskId' => $id_task, ':userId' => $id]);
$hasOwnSubTask = $hasOwnSubTaskQuery->fetch(PDO::FETCH_COLUMN);

$report = $taskClass->get('report');
$idtask = $taskClass->get('id');
$nametask = $taskClass->get('name');
$status = $taskClass->get('status');
if ($status == 'overdue' || $status == 'new' || $status == 'returned') {
    $status = 'inwork';
}
$enableComments = true;
if ($status == 'done' || $status == 'canceled') {
    $enableComments = false;
}
$description = nl2br($taskClass->get('description'));
$description = htmlspecialchars_decode($description);

$tryPremiumLimits = getFreePremiumLimits($idc);
$isPremiumUsed = (boolean)$taskClass->get('with_premium');

$actualDeadline = $taskClass->get('datedone');
$datedone = date("d.m", $actualDeadline);
$dayDone = date('j', $actualDeadline);
$monthDone = $_months[date('n', $actualDeadline) - 1];
$datecreateSeconds = $taskClass->get('datecreate');
$dayost = (strtotime($datedone) - strtotime('midnight')) / (60 * 60 * 24);
$dateProgress = $taskClass->getDateProgress();

$checklist = $taskClass->getCheckList();

$view = $taskClass->get('view');
if ($worker == $id && $view == '0') {
    $taskClass->markTaskAsRead();
}
$taskClass->markTaskEventsAsRead();


if ($idc == $taskClass->get('idcompany') && ($id == $manager || $isCeo || $manager == 1)) {
    $role = 'manager';
} elseif ((in_array($id, $coworkersId) || $worker == $id || $hasOwnSubTask) && $status != 'planned') {
    $role = 'worker';
} else {
    header('Location: /tasks/');
    exit();
}

$isCoworker = in_array($id, $coworkersId);

$viewer = $pdo->prepare('UPDATE `comments` SET view = "1" where idtask="' . $id_task . '" and iduser!=' . $id);
$viewer->execute();

//измменяем статус "в работе"
if ($id == $worker and $status == 'new' || $status == 'returned') {
    $taskClass->updateTaskStatus('inwork');
}

ob_end_flush();

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];

$enableEdit = ($isCeo || $manager == $id) && !in_array($status, ['done', 'canceled']) && $manager != 1;

$parentTaskId = $taskClass->get('parent_task');
if (!is_null($parentTaskId)) {
    $parentTaskName = DBOnce('name', 'tasks', 'id= ' . $parentTaskId);
}

