<?php

function prepareTasks(&$tasks)
{
    global $id;
    global $roleu;
    global $_months;
    foreach ($tasks as &$task) {
        if (!is_null($task['datepostpone']) && $task['datepostpone'] != 0) {
            $task['datedone'] = $task["datepostpone"];
        }
        $task['dateProgress'] = getDateProgress($task['datedone'], $task['datecreate']);
        $task['deadLineDay'] = date('j', $task['datedone']);
        $task['deadLineMonth'] = $_months[date('n', $task['datedone']) - 1];
        $task['classRole'] = '';
        if ($task['idworker'] == $id) {
            $task['classRole'] .= ' worker';
            $task['mainRole'] = 'worker';
        }
        if ($task['idmanager'] == $id || $roleu == 'ceo') {
            $task['classRole'] .= ' manager';
            $task['mainRole'] = 'manager';

        }
        $task['coworkers'] = explode(',', $task['taskCoworkers']);
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

