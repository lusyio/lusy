<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');
$cron = true;

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/task-functions.php';


$plannedTasksQuery = $pdo->prepare('SELECT id, idcompany, manager, worker, author, datedone FROM tasks WHERE status = :oldStatus AND datecreate < :nowTime');
$plannedTasksQuery->execute(array(':oldStatus' => 'planned', 'nowTime' => time()));
$plannedTasks = $plannedTasksQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($plannedTasks as $task) {
    $id = $task['manager'];
    $idc = $task['idcompany'];
    resetViewStatus($task['id']);

    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $task['id']));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN);

    addTaskCreateComments($task['id'], $task['worker'], $coworkers);
    addEvent('createtask', $task['id'], $task['datedone'], $task['worker']);
}

$makeInworkTasksQuery = $pdo->prepare('UPDATE tasks SET status = :newStatus WHERE status = :oldStatus AND datecreate < :nowTime');
$makeInworkTasksQuery->execute(array(':newStatus' => 'new', ':oldStatus' => 'planned', 'nowTime' => time()));

