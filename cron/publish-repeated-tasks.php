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
require_once __ROOT__ . '/engine/backend/classes/Task.php';

$repeatedTasksQuery = $pdo->prepare("SELECT id, repeat_task, repeat_type, datecreate, datedone FROM tasks WHERE status NOT IN ('planned') AND repeat_type > 0 ORDER BY datecreate DESC");
$repeatedTasksQuery->execute();
$repeatedTasks = $repeatedTasksQuery->fetchAll(PDO::FETCH_ASSOC);

$tasksToRepeat = [];

foreach ($repeatedTasks as $row) {
    if (is_null($row['repeat_task'])) {
        if (!(isset($tasksToRepeat[$row['id']])) || $row['datecreate'] > $tasksToRepeat[$row['id']]['datecreate']) {
            $tasksToRepeat[$row['id']]['datecreate'] = $row['datecreate'];
            $tasksToRepeat[$row['id']]['taskToCopy'] = $row['id'];
            $tasksToRepeat[$row['id']]['repeatType'] = $row['repeat_type'];
            $tasksToRepeat[$row['id']]['datedone'] = $row['datedone'];
        }
    } else {
        if (!(isset($tasksToRepeat[$row['repeat_task']])) || $row['datecreate'] > $tasksToRepeat[$row['repeat_task']]['datecreate']) {
            $tasksToRepeat[$row['repeat_task']]['datecreate'] = $row['datecreate'];
            $tasksToRepeat[$row['repeat_task']]['taskToCopy'] = $row['id'];
            $tasksToRepeat[$row['repeat_task']]['repeatType'] = $row['repeat_type'];
            $tasksToRepeat[$row['repeat_task']]['datedone'] = $row['datedone'];
        }
    }
}

foreach ($tasksToRepeat as $taskId => $data) {
    if ($data['repeatType'] == 0) {
        continue;
    }
    $newDateCreate = $data['datecreate'] + $data['repeatType'] * 3600 * 24;
    if (time() >=  $newDateCreate) {
        $newDateDone = $newDateCreate + ($data['datedone'] - $data['datecreate']);
        $newTask = Task::repeatTask($data['taskToCopy'], $newDateCreate, $newDateDone, $taskId, $data['repeatType']);
    }
}
