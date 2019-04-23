<?php
$id_task = $_GET['task'];
$id = $GLOBALS["id"];
global $pdo;
global $datetime;
$task = DB('id, name, status, description, manager, worker, view, datecreate, datedone, datepostpone, report', 'tasks', 'id = "' . $id_task . '" LIMIT 1')[0];
$report = $task['report'];
$idtask = $task['id'];
$nametask = $task['name'];
$description = nl2br($task['description']);
$manager = $task['manager'];
$managername = DBOnce('name', 'users', 'id=' . $manager);
$managersurname = DBOnce('surname', 'users', 'id=' . $manager);
$worker = $task['worker'];
$workername = DBOnce('name', 'users', 'id=' . $worker);
$workersurname = DBOnce('surname', 'users', 'id=' . $worker);
$view = $task['view'];
$viewState = '';
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

if ($id != $worker and $id != $manager) {
    echo "<script>document.location.href = '/tasks/'</script>";
} else {
    if ($id == $worker) {
        $role = 'worker';
    }
    if ($id == $manager) {
        $role = 'manager';
    }
    if ($id == $worker and $id == $manager) {
        $role = 'manager';
    }

    if ($status == 'overdue' || $status == 'new' || $status == 'returned') {
        $status = 'inwork';
    }
}

