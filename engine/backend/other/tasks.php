<?php
require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $supportCometHash;
global $roleu;
global $now;
global $_months;
$cometTrackChannelName = getCometTrackChannelName();

$otbor = '(worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ') and status!="done"';
$usedStatuses = DB('DISTINCT `status`', 'tasks', $otbor);
$statuses = array_column($usedStatuses, 'status');
$sortedUsedStatuses = getSortedStatuses($usedStatuses);

$isManager = DBOnce('id', 'tasks', 'manager=' . $GLOBALS["id"]);
$isWorker = DBOnce('id', 'tasks', 'worker=' . $GLOBALS["id"]);

require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
$taskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');

$taskList->setQueryStatusFilter(['done', 'canceled'], false);
$taskList->setSubTaskFilterString(['done', 'canceled'], false);
$taskList->setParentTaskNullFilterString(true);
$taskList->executeQuery();
$taskList->instantiateTasks();
$taskList->sortActualTasks();
$tasks = $taskList->getTasks();
$countAllTasks = $taskList->countTasks();
unset($taskList);

$doneTaskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');
$doneTaskList->setQueryStatusFilter(['done'], true);
$doneTaskList->setSubTaskFilterString(['done'], true);
$doneTaskList->executeCountQuery();
$countArchiveDoneTasks = $doneTaskList->getCountResult();
unset($doneTaskList);

$canceledTaskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');
$canceledTaskList->setQueryStatusFilter(['canceled'], true);
$canceledTaskList->setSubTaskFilterString(['canceled'], true);
$canceledTaskList->executeCountQuery();
$countArchiveCanceledTasks = $canceledTaskList->getCountResult();
unset($canceledTaskList);

$workersId = [];
$hasOutcomeTasks = false;
$hasIncomeTasks = false;
foreach ($tasks as $task) {
    if ($task->get('worker') == $id && $task->get('worker') != $task->get('manager')) {
        $hasIncomeTasks = true;
    }
    if ($task->get('manager') == $id) {
        $hasOutcomeTasks = true;
    }
    $workersId[] = $task->get('worker');

    if (count($task->get('subTasks')) > 0) {
        $subTasks = $task->get('subTasks');
        foreach ($subTasks as $subTask) {
            if ($subTask->get('worker') == $id && $subTask->get('worker') != $subTask->get('manager')) {
                $hasIncomeTasks = true;
            }
            if ($subTask->get('manager') == $id) {
                $hasOutcomeTasks = true;
            }
            $workersId[] = $subTask->get('worker');
        }
    }
}
$workersId = array_unique($workersId);
sort($workersId);
$workersName = [];
foreach ($workersId as $workerId) {
    $workersName[$workerId] = getDisplayUserName($workerId);
}
