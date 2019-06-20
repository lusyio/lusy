<?php
global $id;
global $idс;
global $pdo;
global $cometHash;
global $cometTrackChannelName;

require_once 'engine/backend/functions/achievement-functions.php';


$completedTasksQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE worker = :workerId AND status = 'done'");
$completedTasksQuery->execute(array(':workerId' => $id));
$completedTasksCount = $completedTasksQuery->fetch(PDO::FETCH_COLUMN);

$assignedTasksQuery = $pdo->prepare("SELECT COUNT(*) FROM tasks WHERE manager = :managerId");
$assignedTasksQuery->execute(array(':managerId' => $id));
$assignedTasksCount = $assignedTasksQuery->fetch(PDO::FETCH_COLUMN);

$userProgress = getUserProgress($id);
$nonMultipleAchievements = getUserNonMultipleAchievements($id);
$workerPath = [
    'TASKDONE_10' => [
        'got' => key_exists('TASKDONE_10', $nonMultipleAchievements),
        'title' => $GLOBALS['_TASKDONE_10'],
        'count' => (key_exists('TASKDONE_10', $nonMultipleAchievements)) ? date('d.m.Y', $nonMultipleAchievements['TASKDONE_10']) : $userProgress['taskDone'] . '/10',
        'value' => (($userProgress['taskDone'] / 10) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 10),
        'text' => 'Завершил 10 задач'
    ],
    'TASKDONE_50' => [
        'got' => key_exists('TASKDONE_50', $nonMultipleAchievements),
        'title' => $GLOBALS['_TASKDONE_50'],
        'count' => (key_exists('_TASKDONE_50', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['_TASKDONE_50']) : $userProgress['taskDone'] . '/50',
        'value' => (($userProgress['taskDone'] / 50) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 50),
        'text' => 'Завершил 50 задач'
    ],
    'TASKDONE_100' => [
        'got' => key_exists('TASKDONE_100', $nonMultipleAchievements),
        'title' => $GLOBALS['_TASKDONE_100'],
        'count' => (key_exists('TASKDONE_100', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['TASKDONE_100']) : $userProgress['taskDone'] . '/100',
        'value' => (($userProgress['taskDone'] / 100) > 1) ? 1 : str_replace(',', '.', $userProgress['taskDone'] / 100),
        'text' => 'Завершил 100 задач'
    ],
    'TASKDONE_200' => [
        'got' => key_exists('TASKDONE_200', $nonMultipleAchievements),
        'title' => $GLOBALS['_TASKDONE_200'],
        'count' => ($userProgress['taskDone'] > 200) ? '200' : $userProgress['taskDone'] . '/200',
        'value' => (key_exists('TASKDONE_200', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['TASKDONE_200']) : str_replace(',', '.', $userProgress['taskDone'] / 200),
        'text' => 'Завершил 200 задач'
    ],
    'TASKDONE_500' => [
        'got' => key_exists('_TASKDONE_500', $nonMultipleAchievements),
        'title' => $GLOBALS['_TASKDONE_500'],
        'count' => ($userProgress['taskDone'] > 500) ? '500' : $userProgress['taskDone'] . '/500',
        'value' => (key_exists('_TASKDONE_500', $nonMultipleAchievements)) ?  date('d.m.Y', $nonMultipleAchievements['_TASKDONE_500']) : str_replace(',', '.', $userProgress['taskDone'] / 500),
        'text' => 'Завершил 500 задач'
    ],
]

?>
