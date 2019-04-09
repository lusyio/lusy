<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include '../engine/conf.php'; // подключаем базу данных
$now = date("Y-m-d");
$data = date("Y-m-d H:i:s");

$news = DB('id, manager, worker, idcompany','tasks','datedone < "'.$now.'" and status = "new"');
foreach ($news as $n) { 
$update = $pdo->prepare('UPDATE `tasks` SET status = "overdue" where id="'.$n['id'].'"');
$update->execute();

// создаем запись в log об изменении статуса задачи
$intolog = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `task` = :idtask, `sender` = :sender, `recipient` = :recipient, `idcompany` = :idcompany, `datetime` = :datetime");
$action = 'overduetask';
$intolog->execute(array('action' => $action, 'idtask' => $n['id'], 'sender' => $n['manager'], 'recipient' => $n['worker'], 'idcompany' => $n['idcompany'], 'datetime' => $data));

}

echo 'ok';?>

