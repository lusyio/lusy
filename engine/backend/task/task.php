<?php
$id_task = filter_var($_GET['task'], FILTER_SANITIZE_NUMBER_INT);
$id = $GLOBALS["id"];
global $pdo;
global $datetime;
$query = 'SELECT t.id, t.name, t.status, t.description, t.manager, t.worker, t.view, t.datecreate, t.datedone, t.datepostpone, t.report, t.view_status, u1.name AS managerName, u1.surname AS managerSurname, u2.name AS workerName, u2.surname AS workerSurname FROM tasks t LEFT JOIN users u1 ON t.manager = u1.id LEFT JOIN users u2 ON t.worker = u2.id WHERE t.id = :taskId';
$dbh = $pdo->prepare($query);
$dbh->execute(array(':taskId' => $id_task));
$task = $dbh->fetch(PDO::FETCH_ASSOC);
$filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
$filesQuery->execute(array(':commentId' => $id_task, ':commentType' => 'task'));
$files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
$coworkersQuery = $pdo->prepare("SELECT tc.worker_id, u.surname, u.name FROM task_coworkers tc LEFT JOIN users u ON tc.worker_id = u.id WHERE tc.task_id = :taskId");
$coworkersQuery->execute(array(':taskId' => $id_task));
$coworkers = $coworkersQuery->fetchAll(PDO::FETCH_ASSOC);
//$task = DB('id, name, status, description, manager, worker, view, datecreate, datedone, datepostpone, report', 'tasks', 'id = "' . $id_task . '" LIMIT 1')[0];
$report = $task['report'];
$idtask = $task['id'];
$nametask = $task['name'];
$description = nl2br($task['description']);
$manager = $task['manager'];
$managername = $task['managerName'];
$managersurname = $task['managerSurname'];
$worker = $task['worker'];
$workername = $task['workerName'];
$workersurname = $task['workerSurname'];
$view = $task['view'];
$viewState = '';
$viewStatus = json_decode($task['view_status'], true);
$datecreate = date("d.m.Y", strtotime($task['datecreate']));
$datedone = date("d.m", strtotime($task['datedone']));
if ($task['datepostpone'] == '0000-00-00' || $task['datepostpone'] == '') {
    $datepostpone = '';
} else {
    $datepostpone = " >> " . date("d.m", strtotime($task['datepostpone']));
}
$nowdate = date("d.m.Y");
$dayost = (strtotime($datedone) - strtotime($nowdate)) / (60 * 60 * 24);
$status = $task['status'];

if(is_null($viewStatus) || !isset($viewStatus[$id]['datetime'])) {
    $viewStatus[$id]['datetime'] = $datetime;
    $viewStatusJson = json_encode($viewStatus);
    $viewQuery = $pdo->prepare('UPDATE `tasks` SET view_status = :viewStatus where id=:taskId');
    $viewQuery->execute(array(':viewStatus' => $viewStatusJson, ':taskId' => $id_task));
}


if (!is_null($viewStatus) && isset($viewStatus[$manager]['datetime'])) {
    $viewStatusTitleManager = 'Просмотрено ' . $viewStatus[$manager]['datetime'];
} else {
    $viewStatusTitleManager = 'Не просмотрено';
}


if ($id == $worker and $view == 0) {
    $viewer = $pdo->prepare('UPDATE `tasks` SET view = "1" where id="' . $idtask . '"');
    $viewer->execute();
    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = 'Просмотрено', `iduser` = :iduser, `idtask` = :idtask, `status` = 'system', `view`=0, `datetime` = :datetime");
    $sql->execute(array('iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
    $points = DBOnce('points', 'users', 'id=' . $id);
    $points = $points + 5;
    $viewer = $pdo->prepare('UPDATE `users` SET points = "' . $points . '" where id="' . $id . '"');
    $viewer->execute();
}
$viewer = $pdo->prepare('UPDATE `comments` SET view = "1" where idtask="' . $id_task . '" and iduser!=' . $id);
$viewer->execute();

//измменяем статус "в работе"

if ($id == $worker and $status == 'new' || $status == 'returned') {
    $viewer = $pdo->prepare('UPDATE `tasks` SET status = "inwork" where id="' . $idtask . '"');
    $viewer->execute();
}

if ($id == $manager) {
    $role = 'manager';
} elseif ($id == $worker) {
    $role = 'worker';
} else {
    header('Location: /tasks/');
    exit();
}
if (isset($_COOKIE[$id_task])){
    $lastTimeVisit = $_COOKIE[$id_task];
}
ob_end_flush();
if ($status == 'overdue' || $status == 'new' || $status == 'returned') {
    $status = 'inwork';
}
