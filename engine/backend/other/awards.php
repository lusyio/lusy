<?php
global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;

require_once 'engine/backend/functions/achievement-functions.php';
require_once 'engine/backend/functions/log-functions.php';


$completedTasksQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE worker = :workerId AND status = 'done'");
$completedTasksQuery->execute(array(':workerId' => $id));
$completedTasksCount = $completedTasksQuery->fetch(PDO::FETCH_COLUMN);

$assignedTasksQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE manager = :managerId");
$assignedTasksQuery->execute(array(':managerId' => $id));
$assignedTasksCount = $assignedTasksQuery->fetch(PDO::FETCH_COLUMN);

$achievementEventsQuery = $pdo->prepare('SELECT event_id FROM events WHERE action = :action AND recipient_id = :userId AND view_status=0');
$achievementEventsQuery->execute(array(':action' => 'newachievement', ':userId' => $id));
$achievementEvents = $achievementEventsQuery->fetchAll(PDO::FETCH_COLUMN);
markAsRead($achievementEvents);

$userProgress = getUserProgress($id);
$nonMultipleAchievements = getUserNonMultipleAchievements($id);
$workerPath = [
    'taskDone_10' => [
        'got' => key_exists('taskDone_10', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskDone_10'],
        'count' => (key_exists('taskDone_10', $nonMultipleAchievements)) ? date('d.m.Y', $nonMultipleAchievements['taskDone_10']['datetime']) : $userProgress['taskDone'] . '/10',
        'value' => (($userProgress['taskDone'] / 10) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 10),
    ],
    'taskDone_50' => [
        'got' => key_exists('taskDone_50', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskDone_50'],
        'count' => (key_exists('_taskDone_50', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['_taskDone_50']['datetime']) : $userProgress['taskDone'] . '/50',
        'value' => (($userProgress['taskDone'] / 50) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 50),
    ],
    'taskDone_100' => [
        'got' => key_exists('taskDone_100', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskDone_100'],
        'count' => (key_exists('taskDone_100', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['taskDone_100']['datetime']) : $userProgress['taskDone'] . '/100',
        'value' => (($userProgress['taskDone'] / 100) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 100),
    ],
    'taskDone_200' => [
        'got' => key_exists('taskDone_200', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskDone_200'],
        'count' => ($userProgress['taskDone'] > 200) ? '200' : $userProgress['taskDone'] . '/200',
        'value' => (key_exists('taskDone_200', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['taskDone_200']['datetime']) : str_replace(',', '.', $userProgress['taskDone'] / 200),
    ],
    'taskDone_500' => [
        'got' => key_exists('taskDone_500', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskDone_500'],
        'count' => ($userProgress['taskDone'] > 500) ? '500' : $userProgress['taskDone'] . '/500',
        'value' => (key_exists('_taskDone_500', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['_taskDone_500']['datetime']) : str_replace(',', '.', $userProgress['taskDone'] / 500),
    ],
];

$managerPath = [
    'taskCreate_10' => [
        'got' => key_exists('taskCreate_10', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskCreate_10'],
        'count' => (key_exists('taskCreate_10', $nonMultipleAchievements)) ? date('d.m.Y', $nonMultipleAchievements['taskCreate_10']['datetime']) : $userProgress['taskCreate'] . '/10',
        'value' => (($userProgress['taskCreate'] / 10) > 1) ? 1 : str_replace(',', '.', $userProgress['taskCreate'] / 10),
    ],
    'taskCreate_50' => [
        'got' => key_exists('taskCreate_50', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskCreate_50'],
        'count' => (key_exists('_taskCreate_50', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['_taskCreate_50']['datetime']) : $userProgress['taskCreate'] . '/50',
        'value' => (($userProgress['taskCreate'] / 50) > 1) ? 1 : str_replace(',', '.', $userProgress['taskCreate'] / 50),
    ],
    'taskCreate_100' => [
        'got' => key_exists('taskCreate_100', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskCreate_100'],
        'count' => (key_exists('taskCreate_100', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['taskCreate_100']['datetime']) : $userProgress['taskCreate'] . '/100',
        'value' => (($userProgress['taskCreate'] / 100) > 1) ? 1 : str_replace(',', '.', $userProgress['taskCreate'] / 100),
    ],
    'taskCreate_200' => [
        'got' => key_exists('taskCreate_200', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskCreate_200'],
        'count' => ($userProgress['taskCreate'] > 200) ? '200' : $userProgress['taskCreate'] . '/200',
        'value' => (key_exists('taskCreate_200', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['taskCreate_200']['datetime']) : str_replace(',', '.', $userProgress['taskCreate'] / 200),
    ],
    'taskCreate_500' => [
        'got' => key_exists('taskCreate_500', $nonMultipleAchievements),
        'title' => $GLOBALS['_taskCreate_500'],
        'count' => ($userProgress['taskCreate'] > 500) ? '500' : $userProgress['taskCreate'] . '/500',
        'value' => (key_exists('taskCreate_500', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['taskCreate_500']['datetime']) : str_replace(',', '.', $userProgress['taskCreate'] / 500),
    ],
];

$otherAchievementsList = getNonPathAchievements();
$achievementConditions = getAchievementConditions(true);
$achievementConditionsValues = [];
foreach ($achievementConditions as $name => $cond) {
    $conditions = json_decode($cond['conditions'], true);
    $ruleName = array_key_first($conditions);
    $achievementConditionsValues[$name] = [
        'ruleName' => $ruleName,
        'value' => array_shift($conditions)['value'],
    ];
    unset($ruleName);
}
$otherAchievements = [];
$onceAchievements = [
    'invitor', 'meeting', 'taskDone_1', 'taskOverdue_1', 'taskPostpone_1', 'taskDoneWithCoworker_1', 'message_1', 'bugReport_1', 'selfTask_1', 'taskOverduePerMonth_0',
];
foreach ($otherAchievementsList as $a) {
    $goal = $achievementConditionsValues[$a]['value'];
    if (is_bool($goal)) {
        if ($goal) {
            $goal = 1;
        } else {
            $goal = 0;
        }
    }
    if ($achievementConditions[$a]['hidden'] == '0') {
        $hidden = false;
    } else {
        $hidden = true;
    }
    $ruleName = $achievementConditionsValues[$a]['ruleName'];
    $got = key_exists($a, $nonMultipleAchievements);
    $otherAchievements[$a] = [
        'hidden' => $hidden,
        'got' => $got,
        'title' => $GLOBALS['_' . $a],
        'count' => ($userProgress[$ruleName] > $goal) ? $goal : intval($userProgress[$ruleName]) . '/' . $goal,
        'value' => ($got) ?  date('d.m.Y', $nonMultipleAchievements[$a]['datetime']) : str_replace(',', '.', ($goal == 0) ? '0' : $userProgress[$ruleName] / $goal)
    ];
    if ($a == 'taskDonePerMonth_leader') {
        $usersCount = DBOnce('COUNT(*)', 'users', 'idcompany = ' . $idc . ' AND is_fired = 0');
        $otherAchievements[$a]['count'] = $userProgress[$ruleName] . ' ' . $GLOBALS['_place'];
        $otherAchievements[$a]['value'] = str_replace(',', '.',1 - (($userProgress[$ruleName] - 1) / ($usersCount - 1)));
    }
    if (in_array($a, $onceAchievements)) {
        $otherAchievements[$a]['count'] = $GLOBALS['_notAchieved'];
    }
}
?>
