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

function getEventsForUser()
{
    global $id;
    global $idc;
    global $pdo;

    $eventsQuery = $pdo->prepare('SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS commentId, c.comment AS commentText, e.datetime, e.view_status, t.name AS taskName FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE e.recipient_id = :userId OR (e.recipient_id = 0 AND e.company_id = :companyId)
  ORDER BY e.datetime DESC');

    $eventsQuery->execute(array(':userId' =>$id, ':companyId' =>$idc));
    $events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

function getEventByIdForUser($eventId)
{
    global $id;
    global $idc;
    global $pdo;

    $eventsQuery = $pdo->prepare('SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS commentId, c.comment AS commentText, e.datetime, e.view_status, t.name AS taskName FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE (e.recipient_id = :userId OR (e.recipient_id = 0 AND e.company_id = :companyId)) AND e.event_id = :eventId
  ORDER BY e.datetime DESC');

    $eventsQuery->execute(array(':userId' =>$id, ':companyId' =>$idc, ':eventId' => $eventId));
    $events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

function getAllEvents()
{
    global $idc;
    global $pdo;

    $eventsQuery = $pdo->prepare('SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS commentId, c.comment AS commentText, e.datetime, e.view_status, t.name AS taskName FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE e.company_id = :companyId
  ORDER BY e.datetime DESC');

    $eventsQuery->execute(array(':companyId' =>$idc));
    $events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

function prepareEvents(&$events)
{
    foreach ($events as &$event) {
        $event['link'] = '';
        if ($event['action'] == 'comment') {
            $event['link'] = 'task/' . $event['task_id'] . '/#' . $event['commentId'];
            $event['taskname'] = DBOnce('name','tasks','id='.$event['task_id']);
        } else if ($event['action'] == 'newUserRegistered') {
            $event['link'] = 'profile/' . $event['commentId'] . '/';
            $event['name'] = DBOnce('name', 'users', 'id = ' . $event['commentId']);
            $event['surname'] = DBOnce('surname', 'users', 'id = ' . $event['commentId']);
        } else {
            $event['link'] = 'task/' . $event['task_id'] . '/';
            $event['taskname'] = DBOnce('name','tasks','id='.$event['task_id']);
        }
    }
    unset($event);
}

function renderEvent($event)
{
    $systemEvents = [
        'sendInvite', 'newUserRegistered', 'newCompanyRegistered',
    ];

    if ($event['action'] == 'comment') {
        include 'engine/frontend/event-messages/comment.php';
    } else if (in_array($event['action'], $systemEvents)) {
        include 'engine/frontend/event-messages/system.php';
    } else {
        include 'engine/frontend/event-messages/task.php';
    }
}

