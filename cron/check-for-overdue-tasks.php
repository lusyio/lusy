<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/task-functions.php';


$overdueTasksQuery = $pdo->prepare("SELECT id FROM tasks WHERE status IN ('new', 'inwork', 'returned') AND datedone < :nowTime AND manager > 1");
$overdueTasksQuery->execute(array('nowTime' => time()-86400));
$overdueTasks = $overdueTasksQuery->fetchAll(PDO::FETCH_COLUMN);

$overdueSystemTasksQuery = $pdo->prepare("SELECT id FROM tasks WHERE status IN ('new', 'inwork', 'returned') AND datedone < :nowTime AND manager = 1");
$overdueSystemTasksQuery->execute(array('nowTime' => time()-86400));
$overdueSystemTasks = $overdueTasksQuery->fetchAll(PDO::FETCH_COLUMN);

$makeOverdueTasksQuery = $pdo->prepare("UPDATE tasks SET status = :newStatus WHERE status IN ('new', 'inwork', 'returned') AND datedone < :nowTime");
$makeOverdueTasksQuery->execute(array(':newStatus' => 'overdue', 'nowTime' => time()-86400));

$makeDoneSystemTaskQuery = $pdo->prepare("UPDATE tasks SET datedone = datedone + 86400 WHERE status IN ('new', 'inwork', 'returned') AND datedone < :nowTime AND manager = 1");
$makeDoneSystemTaskQuery->execute(array('nowTime' => time()-86400));


foreach ($overdueTasks as $task) {
    addEvent('overdue', $task, '');
}
unset($task);
foreach ($overdueSystemTasks as $task) {
    addEvent('workdone', $task, '');
}
