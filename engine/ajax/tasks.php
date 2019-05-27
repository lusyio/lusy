<?php

require_once 'engine/backend/functions/tasks-functions.php';

global $id;
global $idc;
global $pdo;

if ($_POST['module'] == 'loadArchiveTasks') {
    $tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.datepostpone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view,
       IF(t.datepostpone IS NULL OR t.datepostpone='0000-00-00', t.datedone, t.datepostpone) AS sort_date,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE (manager=:userId OR worker=:userId) AND t.status IN ('done', 'canceled') ORDER BY sort_date";
    $dbh = $pdo->prepare($tasksQuery);
    $dbh->execute(array(':userId' => $id));
    $tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
    $countArchiveTasks = count($tasks);
    prepareTasks($tasks);

    $borderColor = [
        'new' => 'border-primary',
        'inwork' => 'border-primary',
        'overdue' => 'border-danger',
        'postpone' => 'border-warning',
        'pending' => 'border-warning',
        'returned' => 'border-primary',
        'done' => 'border-success',
        'canceled' => 'border-secondary',
    ];
    $taskStatusText = [
        'manager' => [
            'new' => $GLOBALS['_tasknewmanager'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'worker' => [
            'new' => $GLOBALS['_tasknewworker'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
    ]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
    foreach ($tasks as $n):
        if (isset($_COOKIE[$n['idtask']]) && $_COOKIE[$n['idtask']] < strtotime($n['lastCommentTime'])) {
            $hasNewComments = true;
        } else {
            $hasNewComments = false;
        }
        if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$n['idmanager']])) {
            $viewStatusTitleManager = 'Просмотрено ' . $n['viewStatus'][$n['idmanager']]['datetime'];
        } else {
            $viewStatusTitleManager = 'Не просмотрено';
        }
        if (is_null($n['viewStatus']) || !isset($n['viewStatus'][$id])) {
            $isTaskRead = false;
        } else {
            $isTaskRead = true;
        }
        include 'engine/frontend/other/task-card.php';
    endforeach;
}

if ($_POST['module'] == 'loadDoneTasks') {
    $limit = 20;
    if (isset($_POST['offset'])) {
        $offset = filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $offset = 0;
    }

    $tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.datepostpone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view,
       IF(t.datepostpone IS NULL OR t.datepostpone='0000-00-00', t.datedone, t.datepostpone) AS sort_date,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE (manager=:userId OR worker=:userId) AND t.status = 'done' ORDER BY sort_date LIMIT :limit OFFSET :offset";
    $dbh = $pdo->prepare($tasksQuery);
    $dbh->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $dbh->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $dbh->bindValue(':userId', (int) $id, PDO::PARAM_INT);
    $dbh->execute();
    $tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
    $countArchiveTasks = count($tasks);
    prepareTasks($tasks);

    $borderColor = [
        'new' => 'border-primary',
        'inwork' => 'border-primary',
        'overdue' => 'border-danger',
        'postpone' => 'border-warning',
        'pending' => 'border-warning',
        'returned' => 'border-primary',
        'done' => 'border-success',
        'canceled' => 'border-secondary',
    ];
    $taskStatusText = [
        'manager' => [
            'new' => $GLOBALS['_tasknewmanager'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'worker' => [
            'new' => $GLOBALS['_tasknewworker'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
    ]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
    foreach ($tasks as $n):
        if (isset($_COOKIE[$n['idtask']]) && $_COOKIE[$n['idtask']] < strtotime($n['lastCommentTime'])) {
            $hasNewComments = true;
        } else {
            $hasNewComments = false;
        }
        if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$n['idmanager']])) {
            $viewStatusTitleManager = 'Просмотрено ' . $n['viewStatus'][$n['idmanager']]['datetime'];
        } else {
            $viewStatusTitleManager = 'Не просмотрено';
        }
        if (is_null($n['viewStatus']) || !isset($n['viewStatus'][$id])) {
            $isTaskRead = false;
        } else {
            $isTaskRead = true;
        }
        include 'engine/frontend/other/task-card.php';
    endforeach;
}

if ($_POST['module'] == 'loadCanceledTasks') {
    $limit = 20;
    if (isset($_POST['offset'])) {
        $offset = filter_var($_POST['offset'], FILTER_SANITIZE_NUMBER_INT) * $limit;
    } else {
        $offset = 0;
    }

    $tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.view_status, t.name, t.description, t.datecreate, t.datedone, t.datepostpone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view,
       IF(t.datepostpone IS NULL OR t.datepostpone='0000-00-00', t.datedone, t.datepostpone) AS sort_date,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT c.datetime FROM comments c WHERE c.status='comment' AND c.idtask = t.id ORDER BY c.datetime DESC LIMIT 1) AS lastCommentTime,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE (manager=:userId OR worker=:userId) AND t.status = 'canceled' ORDER BY sort_date LIMIT :limit OFFSET :offset";
    $dbh = $pdo->prepare($tasksQuery);
    $dbh->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $dbh->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $dbh->bindValue(':userId', (int) $id, PDO::PARAM_INT);
    $dbh->execute();
    $tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
    $countArchiveTasks = count($tasks);
    prepareTasks($tasks);

    $borderColor = [
        'new' => 'border-primary',
        'inwork' => 'border-primary',
        'overdue' => 'border-danger',
        'postpone' => 'border-warning',
        'pending' => 'border-warning',
        'returned' => 'border-primary',
        'done' => 'border-success',
        'canceled' => 'border-secondary',
    ];
    $taskStatusText = [
        'manager' => [
            'new' => $GLOBALS['_tasknewmanager'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
        'worker' => [
            'new' => $GLOBALS['_tasknewworker'],
            'inwork' => $GLOBALS['_inprogresslist'],
            'overdue' => $GLOBALS['_overduelist'],
            'postpone' => $GLOBALS['_postponelist'],
            'pending' => $GLOBALS['_pendinglist'],
            'returned' => $GLOBALS['_returnedlist'],
            'done' => $GLOBALS['_donelist'],
            'canceled' => $GLOBALS['_canceledlist'],
        ],
    ]; //for example: $taskStatusText[$n['mainRole']][$n['status']]
    foreach ($tasks as $n):
        if (isset($_COOKIE[$n['idtask']]) && $_COOKIE[$n['idtask']] < strtotime($n['lastCommentTime'])) {
            $hasNewComments = true;
        } else {
            $hasNewComments = false;
        }
        if (!is_null($n['viewStatus']) && isset($n['viewStatus'][$n['idmanager']])) {
            $viewStatusTitleManager = 'Просмотрено ' . $n['viewStatus'][$n['idmanager']]['datetime'];
        } else {
            $viewStatusTitleManager = 'Не просмотрено';
        }
        if (is_null($n['viewStatus']) || !isset($n['viewStatus'][$id])) {
            $isTaskRead = false;
        } else {
            $isTaskRead = true;
        }
        include 'engine/frontend/other/task-card.php';
    endforeach;
}