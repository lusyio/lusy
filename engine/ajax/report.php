<?php

global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once __ROOT__ . '/engine/backend/functions/settings-functions.php';
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'personalStat') {
    $workerId = filter_var($_POST['workerId'], FILTER_SANITIZE_NUMBER_INT);
    $workerIdc = DBOnce('idcompany', 'users', 'id=' . $workerId);
    if ($workerIdc != $idc) {
        exit;
    }

    $startDateInput = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING);
    $endDateInput = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING);
    $startDate = strtotime($startDateInput);
    $endDate = strtotime($endDateInput) + 3600 *24;
    $stats = getPersonalStats($workerId, $startDate, $endDate);
    echo json_encode($stats);
}

function getPersonalStats($userId, $startDate, $endDate)
{
    global $pdo;

    $doneIncomeQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE t.status = 'done' AND t.worker = :userId AND e.action = 'workdone' AND e.datetime >= :startDate AND e.datetime < :endDate AND t.worker <> t.manager");
    $doneIncomeQuery->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
    $doneIncomeQuery->bindValue(':startDate', (int) $startDate, PDO::PARAM_INT);
    $doneIncomeQuery->bindValue(':endDate', (int) $endDate, PDO::PARAM_INT);
    $doneIncomeQuery->execute();
    $doneIncome = $doneIncomeQuery->fetch(PDO::FETCH_COLUMN);

    $doneOutcomeQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE t.status = 'done' AND t.manager = :userId AND e.datetime >= :startDate AND e.datetime < :endDate");
    $doneOutcomeQuery->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
    $doneOutcomeQuery->bindValue(':startDate', (int) $startDate, PDO::PARAM_INT);
    $doneOutcomeQuery->bindValue(':endDate', (int) $endDate, PDO::PARAM_INT);
    $doneOutcomeQuery->execute();
    $doneOutcome = $doneOutcomeQuery->fetch(PDO::FETCH_COLUMN);

    $tasksQuery = $pdo->prepare("SELECT DISTINCT t.id, t.name, t.description, t.status, t.manager, u1.name AS managerName, u1.surname AS managerSurname, u1.email AS managerEmail, t.worker, u2.name AS workerName, u2.surname AS workerSurname, u2.email AS workerEmail FROM tasks t LEFT JOIN events e ON t.id = e.task_id LEFT JOIN users u1 ON t.manager = u1.id LEFT JOIN users u2 ON t.worker = u2.id  WHERE (t.manager = :userId OR t.worker = :userId) AND ((e.action IN ('workdone', 'canceltask') AND e.datetime >=:startDate AND t.datecreate < :endDate) OR (e.action IN ('new', 'inwork', 'overdue', 'postpone', 'pending', 'returned') AND t.datecreate < :endDate))");
    $tasksQuery->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
    $tasksQuery->bindValue(':startDate', (int) $startDate, PDO::PARAM_INT);
    $tasksQuery->bindValue(':endDate', (int) $endDate, PDO::PARAM_INT);
    $tasksQuery->execute();
    $tasks = $tasksQuery->fetchAll(PDO::FETCH_ASSOC);
    foreach ($tasks as &$task) {
        $task['managerFullName'] = trim($task['managerName'] . ' ' . $task['managerSurname']);
        if ($task['managerFullName'] == '') {
            $task['managerFullName'] = $task['managerEmail'];
        }
        $task['workerFullName'] = trim($task['workerName'] . ' ' . $task['workerSurname']);
        if ($task['workerFullName'] == '') {
            $task['workerFullName'] = $task['workerEmail'];
        }
    }

    $overdueQuery = $pdo->prepare("SELECT COUNT(*) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE e.action = 'overdue' AND t.worker = :userId AND e.datetime >= :startDate AND e.datetime < :endDate");
    $overdueQuery->bindValue(':userId', (int) $userId, PDO::PARAM_INT);
    $overdueQuery->bindValue(':startDate', (int) $startDate, PDO::PARAM_INT);
    $overdueQuery->bindValue(':endDate', (int) $endDate, PDO::PARAM_INT);
    $overdueQuery->execute();
    $overdue = $overdueQuery->fetch(PDO::FETCH_COLUMN);

    $result = [
        'startDate' => $startDate,
        'endDate' => $endDate,
        'doneIncome' => $doneIncome,
        'doneOutcome' => $doneOutcome,
        'overdue' => $overdue,
        'tasks' => $tasks
    ];
    return $result;
}