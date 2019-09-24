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
    $parentTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone 
FROM tasks 
WHERE (manager = :managerId OR worker = :managerId) AND ((status NOT IN ('done', 'canceled', 'planned')) OR (status = 'planned' AND (manager = :managerId OR worker = :managerId))) AND parent_task IS NULL");
    $parentTasksQuery->execute([':managerId' => $id]);
    $parentTasks = $parentTasksQuery->fetchAll(PDO::FETCH_ASSOC);
}
$users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');

if (isset($_GET['edit']) && $_GET['edit'] == 1) {
    require_once __ROOT__ . '/engine/backend/classes/Task.php';
    $task = new Task($_GET['task']);
    $taskId = $task->get('id');
    $taskName = $task->get('name');
    $taskDescription = htmlspecialchars($task->get('description'));
    $taskDescription = decodeTextTags($taskDescription);
    $taskAuthorId = $task->get('author');
    $manager = $task->get('manager');
    $worker = $task->get('worker');
    $startDate = $task->get('datecreate');
    $taskDatedone = $task->get('datedone');
    $parentTask = $task->get('parent_task');
    $checklist = json_decode($task->get('checklist'), true);
    if (is_null($checklist)) {
        $checklist = [];
    }
    $taskCoworkers = $task->get('coworkers');
    $taskUploads = $task->get('files');
    $hasCloudUploads = false;
    foreach ($taskUploads as $file) {
        if ($file['cloud'] == 1) {
            $hasCloudUploads = true;
            break;
        }
    }
    $taskStatus = $task->get('status');
    $withPremium = $task->get('with_premium');
    $repeatType = $task->get('repeat_type');
    $taskEdit = true;
    $hasSubTasks = false;
    if (count($task->get('subTasks')) > 0) {
        $hasSubTasks = true;
    }

    if (in_array($taskStatus, ['done', 'canceled'])) {
        header('Location: ../');
        exit;
    }
    if ($manager == 1 || $idc != $task->get('idcompany') || ($tariff == 0 && $tryPremiumLimits['edit'] >= 3 || (!$isCeo && $id != $manager))) {
        header('Location: ../');
        exit();
    }

}
