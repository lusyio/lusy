<?php

$pointsRules = [
    'createtask' => 100,
    'comment' => 5,
    'taskdone' => 100,
];

$countableAchievementsRules = [
    'firstTask' => [
        'filter' => 'createtask',
        'amount' => 1,
    ],
    'firstComment' => [
        'filter' => 'comment',
        'amount' => 1,
    ],
    'firstTaskDone' => [
        'filter' => 'taskdone',
        'amount' => 1,
    ],
];

function logAction($userId, $action, $taskId = null)
{
    global $pdo;
    global $datetime;
    global $pointsRules;
    if (array_key_exists($action, $pointsRules))
    {
        $logQuery = "INSERT INTO log(action, task, sender, datetime) VALUES (:action, :taskId, :userId, :dateTime)";
        $dbh = $pdo->prepare($logQuery);
        $dbh->execute(array(':action' => $action, ':taskId' => $taskId, ':userId' => $userId, ':dateTime' => $datetime));
    }
}

function getAllActions($userId)
{
    global $pdo;
    $query = "SELECT id, action, task, datetime FROM log WHERE sender = :userId";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId));
    $actions = $dbh->fetchColumn(0);
    $actionsCount = $dbh->fetchColumn(1);
    $result = array_combine($actions, $actionsCount);
    return $result;
}

function countActions($userId)
{
    global $pdo;
    $query = "SELECT count(*) AS count, action FROM log WHERE sender = 2 GROUP BY action;";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId));
    $actions = $dbh->fetchAll(PDO::FETCH_ASSOC);
    return $actions;
}

function calculateExperience($userId)
{
    $actions = countActions($userId);
    global $pointsRules;
    $experiencePoints = 0;
    foreach ($actions as $action => $count) {
        if (array_key_exists($action, $pointsRules)) {
            $experiencePoints += $pointsRules[$action['action']] * $count;
        }
    }
    return $experiencePoints;
}

function getAchievements($userId) {
    $actions = countActions($userId);
    global $countableAchievementsRules;
    $userAchievements = [];
    foreach ($countableAchievementsRules as $ach => $rules) {
        if(array_key_exists($rules['filter'], $actions) && $actions[$rules['filter']] >= $rules['amount']) {
            $userAchievements[] = $ach;
        }
    }
    return $userAchievements;
}

