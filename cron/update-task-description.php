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

$taskQuery = $pdo->prepare("SELECT id, description FROM tasks");
$taskQuery->execute();
$tasks = $taskQuery->fetchAll(PDO::FETCH_ASSOC);

$taskUpdateQuery = $pdo->prepare("UPDATE tasks SET description = :newDescription WHERE id = :taskId");
foreach ($tasks as $task) {
    $description = nl2br($task['description']);

    $displayingDescription = htmlspecialchars_decode($description);
    $newDescription = encodeTextTags($displayingDescription);
    $taskUpdateQuery->execute([':taskId' => $task['id'], ':newDescription' => $newDescription]);
}