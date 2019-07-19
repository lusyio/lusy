<?php

require_once __ROOT__ . '/engine/backend/other/tasks.php';

global $id;
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

$remainingLimits = getRemainingLimits();
$isTaskCreateDisabled = $remainingLimits['tasks'] <= 0;
$emptySpace = $remainingLimits['space'];
if ($isCeo) {
    $ceoParentTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone FROM tasks WHERE idcompany = :companyId AND status NOT IN ('done', 'canceled')");
    $ceoParentTasksQuery->execute([':companyId' => $idc]);
    $parentTasks = $ceoParentTasksQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    $parentTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone FROM tasks WHERE (manager = :managerId OR worker = :managerId) AND status NOT IN ('done', 'canceled') AND parent_task IS NULL");
    $parentTasksQuery->execute([':managerId' => $id]);
    $parentTasks = $parentTasksQuery->fetchAll(PDO::FETCH_ASSOC);
}