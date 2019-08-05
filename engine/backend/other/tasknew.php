<?php

global $pdo;
global $id;
global $idc;
global $tariff;
global $cometHash;
global $cometTrackChannelName;
global $supportCometHash;
global $roleu;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}
$taskEdit = false;

$remainingLimits = getRemainingLimits();
$isTaskCreateDisabled = $remainingLimits['tasks'] <= 0;
$emptySpace = $remainingLimits['space'];
$tryPremiumLimits = getFreePremiumLimits($idc);
if ($isCeo) {
    $ceoParentTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone FROM tasks WHERE idcompany = :companyId AND status NOT IN ('done', 'canceled') AND parent_task IS NULL AND manager > 1");
    $ceoParentTasksQuery->execute([':companyId' => $idc]);
    $parentTasks = $ceoParentTasksQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    $parentTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone FROM tasks WHERE (manager = :managerId OR worker = :managerId) AND status NOT IN ('done', 'canceled') AND (status NOT IN ('planned') AND worker = :managerId) AND parent_task IS NULL");
    $parentTasksQuery->execute([':managerId' => $id]);
    $parentTasks = $parentTasksQuery->fetchAll(PDO::FETCH_ASSOC);
}

$users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');

if (isset($_GET['edit']) && $_GET['edit'] == 1) {
    $taskEdit = true;
    $taskDataQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone, parent_task, regular, checklist FROM tasks WHERE id = :taskId");
    $taskDataQuery->execute([':taskId' => $_GET['task']]);
    $taskData = $taskDataQuery->fetch(PDO::FETCH_ASSOC);

    $taskCoworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $taskCoworkersQuery->execute([':taskId' => $_GET['task']]);
    $taskCoworkers = $taskCoworkersQuery->fetchAll(PDO::FETCH_COLUMN);
}
