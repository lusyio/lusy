<?php

function prepareTasks(&$tasks)
{
    global $id;
    global $roleu;
    global $_months;
    foreach ($tasks as &$task) {
        $task['dateProgress'] = getDateProgress($task['datedone'], $task['datecreate']);
        $task['deadLineDay'] = date('j', $task['datedone']);
        $task['deadLineMonth'] = $_months[date('n', $task['datedone']) - 1];
        $task['classRole'] = '';
        if (!empty($task['taskCoworkers']) && !is_null($task['taskCoworkers'])) {
            $task['coworkers'] = explode(',', $task['taskCoworkers']);
        } else {
            $task['coworkers'] = [];
        }
        if (!empty($task['idmanager']) && $task['idmanager'] == $id || $roleu == 'ceo') {
            $task['classRole'] .= ' manager';
            $task['mainRole'] = 'manager';
        } else {
            $task['classRole'] .= ' worker';
            $task['mainRole'] = 'worker';
        }
        $task['countCoworkers'] = count($task['coworkers']);
        $task['viewStatus'] = json_decode($task['view_status'], true);

    }
}

function getDateProgress($finishDate, $createDate)
{
    $dateCreateDateDoneDiff = $finishDate - $createDate;
    if ($finishDate > time()) {
        $daysTotal = $dateCreateDateDoneDiff / (60 * 60 * 24) + 1;
        $daysPassed = ceil((time() - $createDate) / (60 * 60 * 24));
        return round(($daysPassed) * 100 / $daysTotal);
    } else {
        return 100;
    }
}

function getSortedStatuses($usedStatuses)
{
    $statuses = array_column($usedStatuses, 'status');
    $sortedStatuses = [
        'new' => $GLOBALS["_newfilter"],
        'inwork' => $GLOBALS["_inworkfilter"],
        'overdue' => $GLOBALS["_overduefilter"],
        'postpone' => $GLOBALS["_postponefilter"],
        'pending' => $GLOBALS["_pendingfilter"],
    ];

    foreach ($sortedStatuses as $k => $v) {
        if (!in_array($k, $statuses)) {
            unset($sortedStatuses[$k]);
        }
    }
    return $sortedStatuses;
}

function groupTasks($tasks)
{
    $groupedTasks = [];
    foreach ($tasks as $task) {
        if ($task['parent_task']) {
            $groupedTasks[$task['parent_task']]['subTasks'][] = $task;
        } else {
            $groupedTasks[$task['idtask']]['task'] = $task;
        }
    }
    return $groupedTasks;
}
