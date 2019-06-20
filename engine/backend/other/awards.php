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

?>
