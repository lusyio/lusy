<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/task-functions.php';


$overdueTasksQuery = $pdo->prepare('SELECT id FROM tasks WHERE status = :oldStatus AND datedone < :nowTime');
$overdueTasksQuery->execute(array(':oldStatus' => 'inwork','nowTime' => time()-86400));
$overdueTasks = $overdueTasksQuery->fetchAll(PDO::FETCH_COLUMN);

$makeOverdueTasksQuery = $pdo->prepare('UPDATE tasks SET status = :newStatus WHERE status = :oldStatus AND datedone < :nowTime');
$makeOverdueTasksQuery->execute(array(':newStatus' => 'overdue',':oldStatus' => 'inwork','nowTime' => time()-86400));

foreach ($overdueTasks as $task) {
    addEvent('overdue', $task, '');
}
