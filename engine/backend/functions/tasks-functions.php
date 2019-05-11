<?php

function prepareTasks(&$tasks)
{
    global $id;
    global $_months;
    foreach ($tasks as &$task) {
        if (!is_null($task['datepostpone']) && $task['datepostpone'] != '0000-00-00') {
            $task['datedone'] = $task["datepostpone"];
        }
        $task['dateProgress'] = getDateProgress($task['datedone'], $task['datecreate']);
        $task['deadLineDay'] = date('j', strtotime($task['datedone']));
        $task['deadLineMonth'] = $_months[date('n', strtotime($task['datedone'])) - 1];
        $task['classRole'] = '';
        if ($task['idworker'] == $id) {
            $task['classRole'] .= ' worker';
            $task['mainRole'] = 'worker';
        }
        if ($task['idmanager'] == $id) {
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
    $dateCreateDateDoneDiff = strtotime($finishDate) - strtotime($createDate);
    if (strtotime($finishDate) > time()) {
        $daysTotal = $dateCreateDateDoneDiff / (60 * 60 * 24) + 1;
        $daysPassed = ceil((time() - strtotime($createDate)) / (60 * 60 * 24));
        return round(($daysPassed) * 100 / $daysTotal);
    } else {
        return 100;
    }
}

function getSortedStatuses($usedStatuses)
{
    $result = [];
    foreach ($usedStatuses as $status) {
        $result[$status[0] . "filter"] = $GLOBALS["_{$status[0]}filter"];
    }
    asort($result);
    return $result;
}

