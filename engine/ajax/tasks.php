<?php

require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;
global $roleu;

if ($_POST['module'] == 'loadArchiveTasks') {
    $limit = 20;
    if (isset($_POST['offset'])) {
        $offset = filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT) * $limit;
    } else {
        $offset = 0;
    }
    if (isset($_POST['workerId']) && $_POST['workerId'] > 1) {
        $workerId = filter_var($_POST['workerId'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $workerId = false;
    }
    if (isset($_POST['status']) && in_array($_POST['status'], ['done', 'canceled'])) {
        $taskStatus = $_POST['status'];
    } else {
        $result = [
            'hasNextPage' => false,
            'tasks' => '',
        ];
        echo json_encode($result);
        exit;
    }
    if (isset($_POST['taskType']) && in_array($_POST['taskType'], ['in', 'out'])) {
        $taskType = $_POST['taskType'];
    } else {
        $taskType = false;
    }
    require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
    $taskList = TaskListStrategy::createTaskList($id, $idc, $roleu == 'ceo');
    if ($taskStatus == 'done') {
        $taskList->setQueryStatusFilter(['done'], true);
    } elseif ($taskStatus == 'canceled') {
        $taskList->setQueryStatusFilter(['canceled'], true);
    }
    if ($workerId) {
        $taskList->setWorkerFilter($workerId);
    }
    if ($taskType == 'in') {
        $taskList->setWorkerFilter($id);
        $taskList->setSelfTaskFilter(false);
    } elseif ($taskType == 'out') {
        $taskList->setManagerFilter($id);
    }
    $taskList->setSubTaskFilterString([-1], true);
    if (isset($_POST['orderField']) && $_POST['orderField'] != '') {
        if ($_POST['orderField'] == 'name') {
            if (isset($_POST['order']) && $_POST['order'] == 'asc') {
                $taskList->addOrderByName(true);
            } else {
                $taskList->addOrderByName(false);
            }
        } else if ($_POST['orderField'] == 'date') {
            if (isset($_POST['order']) && $_POST['order'] == 'asc') {
                $taskList->addOrderByDate(true);
            } else {
                $taskList->addOrderByDate(false);
            }
        } else {
            $taskList->addOrderByDate(false);
        }
    } else {
        $taskList->addOrderByDate(false);
    }
    $taskList->executeQuery();
    $countArchiveTasks = $taskList->countTasks();
    $hasNextPage = false;
    if ($countArchiveTasks > $offset + $limit) {
        $hasNextPage = true;
    }
    $taskList->setTasksQueryLimitString($limit);
    $taskList->setTasksQueryOffsetString($offset);
    $taskList->executeQuery();
    $taskList->instantiateTasks();
    $tasks = $taskList->getTasks();
    $borderColor = [
        'done' => 'border-success',
        'canceled' => 'border-secondary',

    ];
    $textColor = [
        'done' => 'text-success',
        'canceled' => 'text-secondary',
    ];
    $taskStatusText = [
        'manager' => [
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],

        ],
        'ceo' => [
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'worker' => [
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
    ];
    ob_start();
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
    $tasksOutput = ob_get_clean();
    $result = [
        'hasNextPage' => $hasNextPage,
        'tasks' => $tasksOutput,
    ];
    echo json_encode($result);
}
