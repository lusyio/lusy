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

$task = new Task($id_task);

$author = $task->get('author');
$manager = $task->get('manager');
$worker = $task->get('worker');
$managerName = $task->get('managerDisplayName');
$workerName = $task->get('workerDisplayName');
$coworkersId = $task->get('coworkers');
$files = $task->get('files');

$subTasks = $task->get('subTasks');
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

$report = $task->get('report');
$idtask = $task->get('id');
$nametask = $task->get('name');
$status = $task->get('status');
if ($status == 'new' || $status == 'returned') {
    $status = 'inwork';
}
$enableComments = true;
if ($status == 'done' || $status == 'canceled') {
    $enableComments = false;
}
$description = nl2br($task->get('description'));
$description = htmlspecialchars_decode($description);
$description = htmlspecialchars($description);
$description = decodeTextTags($description);

$tryPremiumLimits = getFreePremiumLimits($idc);
$isPremiumUsed = (boolean)$task->get('with_premium');

$actualDeadline = $task->get('datedone');
$datedone = date("d.m", $actualDeadline);
$dayDone = date('j', $actualDeadline);
$monthDone = $_months[date('n', $actualDeadline) - 1];
$datecreateSeconds = $task->get('datecreate');
$dayost = (strtotime($datedone) - strtotime('midnight')) / (60 * 60 * 24);
$dateProgress = $task->getDateProgress();

$checklist = $task->getCheckList();

$view = $task->get('view');
if ($worker == $id && $view == '0') {
    $task->markTaskAsRead();
}
$task->markTaskEventsAsRead();

$viewStatus = json_decode($task->get('view_status'), true);

if(is_null($viewStatus) || !isset($viewStatus[$id]['datetime'])) {
    $viewStatus[$id]['datetime'] = time();
    $viewStatusJson = json_encode($viewStatus);
    $viewQuery = $pdo->prepare('UPDATE `tasks` SET view_status = :viewStatus where id=:taskId');
    $viewQuery->execute(array(':viewStatus' => $viewStatusJson, ':taskId' => $id_task));
}

if (!is_null($viewStatus) && isset($viewStatus[$manager]['datetime'])) {
    $viewStatusTitleManager = 'Просмотрено ' . $viewStatus[$manager]['datetime'];
} else {
    $viewStatusTitleManager = 'Не просмотрено';
}

if ($idc == $task->get('idcompany') && ($id == $manager || $isCeo || $manager == 1)) {
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
if ($id == $worker && in_array($task->get('status'), ['new', 'returned'])) {
    $task->updateTaskStatus('inwork');
}

ob_end_flush();

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];

$enableEdit = ($isCeo || $manager == $id) && !in_array($status, ['done', 'canceled']) && $manager != 1;

$parentTaskId = $task->get('parent_task');
if (!is_null($parentTaskId)) {
    $parentTaskName = DBOnce('name', 'tasks', 'id= ' . $parentTaskId);
}

