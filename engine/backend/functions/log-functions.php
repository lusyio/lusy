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

function getEventsForUser($limit = 0, $period = 0)
{
    global $id;
    global $idc;
    global $pdo;

    $limitQuery = '';
    if ($limit > 0) {
        $limitQuery = ' LIMIT ' . $limit;
    }
    $periodQuery = '';
    if ($period > 0) {
        $startTime = strtotime('-' . $period . ' days midnight');
        $periodQuery = ' AND (e.datetime >= ' . $startTime . ' OR e.view_status = 0) ';
    }
    $eventsQuery = $pdo->prepare('SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS comment, c.comment AS commentText, e.datetime, e.view_status, t.worker FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE (e.recipient_id = :userId OR (e.recipient_id = 0 AND e.company_id = :companyId))' . $periodQuery . '
  ORDER BY e.view_status, e.datetime DESC' . $limitQuery);

    $eventsQuery->execute(array(':userId' => $id, ':companyId' => $idc));
    $events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

function getEventByIdForUser($eventId)
{
    global $id;
    global $idc;
    global $pdo;

    $eventsQuery = $pdo->prepare('SELECT e.event_id, e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment AS comment, c.comment AS commentText, e.datetime, e.view_status, t.name AS taskName FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE (e.recipient_id = :userId OR (e.recipient_id = 0 AND e.company_id = :companyId)) AND e.event_id = :eventId
  ORDER BY e.datetime DESC');

    $eventsQuery->execute(array(':userId' => $id, ':companyId' => $idc, ':eventId' => $eventId));
    $events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $events;
}

function prepareEvents(&$events)
{
    global $pdo;
    foreach ($events as &$event) {
        $event['link'] = '';
        if ($event['action'] == 'comment') {
            $event['link'] = 'task/' . $event['task_id'] . '/#' . $event['comment'];
            $event['taskname'] = DBOnce('name', 'tasks', 'id=' . $event['task_id']);
        } else if ($event['action'] == 'newUserRegistered') {
            $event['link'] = 'profile/' . $event['comment'] . '/';
            $event['name'] = DBOnce('name', 'users', 'id = ' . $event['comment']);
            $event['surname'] = DBOnce('surname', 'users', 'id = ' . $event['comment']);
        } else {
            $event['link'] = 'task/' . $event['task_id'] . '/';
            $event['taskname'] = DBOnce('name', 'tasks', 'id=' . $event['task_id']);
            $event['datedone'] = DBOnce('datedone', 'tasks', 'id=' . $event['task_id']);
            $workerQuery = $pdo->prepare('SELECT u.name, u.surname, u.id FROM users u LEFT JOIN tasks t ON u.id = t.worker WHERE t.id = :taskId');
            $workerQuery->execute(array(':taskId' => $event['task_id']));
            $worker = $workerQuery->fetch(PDO::FETCH_ASSOC);
            $event['worker'] = $worker['id'];
            $event['workerName'] = $worker['name'];
            $event['workerSurname'] = $worker['surname'];
        }
    }
    unset($event);
}

function renderEvent($event)
{
    global $id;
    global $pdo;
    $systemEvents = [
        'sendInvite', 'newuser', 'userwelcome', 'newcompany', 'newachievement', 'birthday'
    ];

    if ($event['action'] == 'comment') {
        include __ROOT__ . '/engine/frontend/event-messages/comment.php';
    } else if (in_array($event['action'], $systemEvents)) {
        include __ROOT__ . '/engine/frontend/event-messages/system.php';
    } else {
        $executorsQuery = $pdo->prepare('SELECT worker, manager FROM tasks WHERE id = :taskId');
        $executorsQuery->execute(array(':taskId' => $event['task_id']));
        $executors = $executorsQuery->fetch(PDO::FETCH_ASSOC);
        $taskManager = $executors['manager'];
        $taskWorker = $executors['worker'];
        $isSelfTask = ($taskManager == $taskWorker);
        include __ROOT__ . '/engine/frontend/event-messages/task.php';
    }
}

function markAsRead($eventId)
{
    global $id;
    global $pdo;
    $markQuery = $pdo->prepare('UPDATE events SET view_status = 1 WHERE event_id = :eventId AND recipient_id = :userId');
    if (is_array($eventId)) {
        foreach ($eventId as $event) {
            $markQuery->execute(array(':eventId' => $event, ':userId' => $id));
        }
    } else {
        $markQuery->execute(array(':eventId' => $eventId, ':userId' => $id));
    }
}

