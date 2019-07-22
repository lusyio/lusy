<?php
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

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}
$id_task = filter_var($_GET['task'], FILTER_SANITIZE_NUMBER_INT);
$id = $GLOBALS["id"];

$taskQuery = $pdo->prepare('SELECT t.id, t.name, t.status, t.description, t.author, t.manager, t.worker, t.view, t.datecreate, t.datedone, t.report, t.view_status, u1.name AS managerName, u1.surname AS managerSurname, u1.email AS managerEmail,
       u2.name AS workerName, u2.surname AS workerSurname, u2.email AS workerEmail, u3.name AS authorName, u3.surname AS authorSurname, u3.email AS authorEmail FROM tasks t 
  LEFT JOIN users u1 ON t.manager = u1.id 
  LEFT JOIN users u2 ON t.worker = u2.id 
  LEFT JOIN users u3 ON t.author = u2.id 
  WHERE t.id = :taskId');
$taskQuery->execute(array(':taskId' => $id_task));
$task = $taskQuery->fetch(PDO::FETCH_ASSOC);

$filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud
  FROM uploads 
  WHERE comment_id = :commentId and comment_type = :commentType');
$filesQuery->execute(array(':commentId' => $id_task, ':commentType' => 'task'));
$files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);

$coworkersQuery = $pdo->prepare("SELECT tc.worker_id, u.surname, u.name, u.email 
  FROM task_coworkers tc 
  LEFT JOIN users u ON tc.worker_id = u.id 
WHERE tc.task_id = :taskId");
$coworkersQuery->execute(array(':taskId' => $id_task));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_ASSOC);

$subTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone FROM tasks WHERE parent_task = :taskId");
$subTasksQuery->execute([':taskId' => $id_task]);
$subTasks = $subTasksQuery->fetchAll(PDO::FETCH_ASSOC);

$hasOwnSubTaskQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE parent_task = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)");
$hasOwnSubTaskQuery->execute([':taskId' => $id_task, ':userId' => $id]);
$hasOwnSubTask = $hasOwnSubTaskQuery->fetch(PDO::FETCH_COLUMN);

$report = $task['report'];
$idtask = $task['id'];
$nametask = $task['name'];
$status = $task['status'];
$description = nl2br($task['description']);
$description = htmlspecialchars_decode($description);

$author = $task['author'];
$authorname = $task['authorName'];
$authorsurname = $task['authorSurname'];
$authorEmail = $task['authorEmail'];

$manager = $task['manager'];
$managername = $task['managerName'];
$managersurname = $task['managerSurname'];
$managerEmail = $task['managerEmail'];

$worker = $task['worker'];
$workername = $task['workerName'];
$workersurname = $task['workerSurname'];
$workerEmail = $task['workerEmail'];

$datecreate = date("d.m.Y", $task['datecreate']);
$datedone = date("d.m", $task['datedone']);
$actualDeadline = $task['datedone'];

$dayDone = date('j', $actualDeadline);
$monthDone = $_months[date('n', $actualDeadline) - 1];

$nowdate = date("d.m.Y");
$dayost = (strtotime($datedone) - strtotime($nowdate)) / (60 * 60 * 24);

$dateProgress = getDateProgress($task['datedone'], $task['datecreate']);


$view = $task['view'];
$viewState = '';
$viewStatus = json_decode($task['view_status'], true);
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

$taskEventsQuery = $pdo->prepare('SELECT event_id FROM events WHERE task_id = :taskId AND recipient_id = :userId AND view_status=0');
$taskEventsQuery->execute(array(':taskId' => $idtask, ':userId' => $id));
$taskEvents = $taskEventsQuery->fetchAll(PDO::FETCH_COLUMN);
markAsRead($taskEvents);

if ($worker == $id && $view == '0') {
    $setViewedQuery = $pdo->prepare('UPDATE `tasks` SET view = :viewState where id = :taskId');
    $setViewedQuery->execute(array('viewState' => 1, ':taskId' => $idtask));
    $isOldTask = (boolean)DBOnce('count(*)', 'events', 'task_id=' . $idtask . ' and action="viewtask"');
    if (!$isOldTask) {
        addEvent('viewtask', $idtask, '', $manager);
    }
}

$coworkersId = array_column($coworkers, 'worker_id');
if ($id == $manager || $isCeo || $manager == 1) {
    $role = 'manager';
} elseif ((in_array($id,$coworkersId) || $worker == $id || $hasOwnSubTask) && $status != 'planned'){
    $role = 'worker';
} else {
    header('Location: /tasks/');
    exit();
}

$isCoworker = in_array($id,$coworkersId);

$viewer = $pdo->prepare('UPDATE `comments` SET view = "1" where idtask="' . $id_task . '" and iduser!=' . $id);
$viewer->execute();


//измменяем статус "в работе"

if ($id == $worker and $status == 'new' || $status == 'returned') {
    $viewer = $pdo->prepare('UPDATE `tasks` SET status = "inwork" where id="' . $idtask . '"');
    $viewer->execute();
}

if (isset($_COOKIE[$id_task])){
    $lastTimeVisit = $_COOKIE[$id_task];
}
ob_end_flush();

if ($status == 'overdue' || $status == 'new' || $status == 'returned') {
    $status = 'inwork';
}

$enableComments = true;
if ($status == 'done' || $status == 'canceled') {
    $enableComments = false;
}

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];
