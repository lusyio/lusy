<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');
$cron = true;

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

$checklistSelect = $pdo->prepare("SELECT id, checklist FROM tasks WHERE checklist IS NOT NULL");
$checklistSelect->execute();
$checklists = $checklistSelect->fetchAll(PDO::FETCH_ASSOC);

$checklistUpdate = $pdo->prepare("UPDATE tasks SET checklist = :newChecklist WHERE id = :taskId");
foreach ($checklists as $checklist) {
    $old = json_decode($checklist['checklist'], true);
    $nextRowId = 1;
    foreach ($old as $key => $row) {
        $old[$key]['rowId'] = $nextRowId;
        $nextRowId++;
    }
    $newJson = json_encode($old);
    $checklistUpdate->execute([':newChecklist' => $newJson, ':taskId' => $checklist['id']]);
}

