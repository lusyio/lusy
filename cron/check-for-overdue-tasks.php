<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include '/../conf.php'; // подключаем базу данных

$overdueTasksQuery = $pdo->prepare('UPDATE tasks SET status = :newStatus WHERE status = :oldStatus AND ((ISNULL(datepostpone) AND datedone < :nowTime) OR (datepostpone < :nowTime))');
$overdueTasksQuery->execute(array(':newStatus' => 'overdue',':oldStatus' => 'inwork','nowTime' => time()-86400));
