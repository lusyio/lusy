<?php

require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $roleu;

if ($_POST['module'] == 'loadDoneTasks') {
    $limit = 20;
    if (isset($_POST['offset'])) {
        $offset = filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT) * $limit;
    } else {
        $offset = 0;
    }

    require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
    $taskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');

    $taskList->setQueryStatusFilter(['done'], true);
    $taskList->setSubTaskFilterString([-1], true);
    $taskList->setTasksQueryLimitString($limit);
    $taskList->setTasksQueryOffsetString($offset);
    $taskList->addOrderByDate(false);
    $taskList->executeQuery();
    $taskList->instantiateTasks();
    $taskList->sortActualTasks();
    $tasks = $taskList->getTasks();
    $countArchiveTasks = $taskList->countTasks();

    $borderColor = [
        'done' => 'border-success',
    ];
    $textColor = [
        'done' => 'text-success',
    ];
    $taskStatusText = [
        'manager' => [
            'done' => $GLOBALS['_donelist'],
        ],
        'ceo' => [
            'done' => $GLOBALS['_donelist'],
        ],
        'worker' => [
            'done' => $GLOBALS['_donelist'],
        ],
    ];

    foreach ($tasks as $task) {
        $status = $task->get('status');
        $taskId = $task->get('id');
        $mainRole = $task->get('mainRole');
        $subTasks = $task->get('subTasks');
        $name = $task->get('name');
        $countComments = $task->get('countComments');
        $countNewComments = $task->get('countNewComments');
        $countAttachedFiles = $task->get('countAttachedFiles');
        $countNewFiles = $task->get('countNewFiles');
        $datedone = $task->get('datedone');
        $manager = $task->get('manager');
        $worker = $task->get('worker');
        $viewStatus = $task->get('viewStatus');
        include __ROOT__ . '/engine/frontend/other/task-card.php';
    }
}

if ($_POST['module'] == 'loadCanceledTasks') {
    $limit = 20;
    if (isset($_POST['offset'])) {
        $offset = filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT) * $limit;
    } else {
        $offset = 0;
    }

    require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
    $taskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');

    $taskList->setQueryStatusFilter(['canceled'], true);
    $taskList->setSubTaskFilterString(['-1'], true);
    $taskList->setTasksQueryLimitString($limit);
    $taskList->setTasksQueryOffsetString($offset);
    $taskList->addOrderByDate(false);
    $taskList->executeQuery();
    $taskList->instantiateTasks();
    $taskList->sortActualTasks();
    $tasks = $taskList->getTasks();
    $countArchiveTasks = $taskList->countTasks();

    $borderColor = [
        'canceled' => 'border-secondary',
    ];
    $textColor = [
        'canceled' => 'text-secondary',
    ];
    $taskStatusText = [
        'manager' => [
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'ceo' => [
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'worker' => [
            'canceled' => $GLOBALS['_canceledlist'],
        ],
    ];

    foreach ($tasks as $task) {
        $status = $task->get('status');
        $taskId = $task->get('id');
        $mainRole = $task->get('mainRole');
        $subTasks = $task->get('subTasks');
        $name = $task->get('name');
        $countComments = $task->get('countComments');
        $countNewComments = $task->get('countNewComments');
        $countAttachedFiles = $task->get('countAttachedFiles');
        $countNewFiles = $task->get('countNewFiles');
        $datedone = $task->get('datedone');
        $manager = $task->get('manager');
        $worker = $task->get('worker');
        $viewStatus = $task->get('viewStatus');
        include __ROOT__ . '/engine/frontend/other/task-card.php';
    }
}
