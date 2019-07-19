<?php

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

global $pdo;
global $datetime;
global $id;
global $idc;
global $cometHash;
global $cometPdo;
global $cometTrackChannelName;
global $supportCometHash;

if ($idc != 1) {
    header('location: /mail/');
}

$supportPage = true;
$usersQuery = $pdo->prepare("SELECT DISTINCT u.id, u.idcompany AS companyId, u.name, u.surname, u.email, c.idcompany AS companyName, IF(m.view_status = 0 AND m.recipient = 1, 0, 1) AS supportViewStatus FROM mail m LEFT JOIN users u ON (m.sender <> 1 AND m.sender = u.id OR m.recipient <> 1 AND m.recipient = u.id) LEFT JOIN company c ON u.idcompany = c.id WHERE m.sender = 1 OR m.recipient = 1 ORDER BY supportViewStatus, m.datetime DESC");
$usersQuery->execute();
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

$lastMessageQuery = $pdo->prepare("SELECT message_id, mes, sender, recipient, view_status, datetime FROM mail WHERE (sender = :userId AND recipient = 1) OR(sender = 1 AND recipient = :userId) ORDER BY datetime DESC LIMIT 1");
$newMessagesQuery = $pdo->prepare("SELECT COUNT(*) AS count FROM mail WHERE sender = :userId AND recipient = 1 AND view_status = 0");
$messagesGroupedByCompany = [];
foreach ($users as $user) {
    $lastMessageQuery->execute([':userId' => $user['id']]);
    $newMessagesQuery->execute([':userId' => $user['id']]);
    $messagesGroupedByCompany[$user['companyId']]['companyName'] = $user['companyName'];
    $messagesGroupedByCompany[$user['companyId']]['dialogs'][$user['id']]['message'] = $lastMessageQuery->fetch(PDO::FETCH_ASSOC);
    $messagesGroupedByCompany[$user['companyId']]['dialogs'][$user['id']]['newMessages'] = $newMessagesQuery->fetch(PDO::FETCH_ASSOC)['count'];
    $messagesGroupedByCompany[$user['companyId']]['dialogs'][$user['id']]['name'] = $user['name'];
    $messagesGroupedByCompany[$user['companyId']]['dialogs'][$user['id']]['surname'] = $user['surname'];
    $messagesGroupedByCompany[$user['companyId']]['dialogs'][$user['id']]['email'] = $user['email'];
}
$newMessagesByCompanyQuery = $pdo->prepare("SELECT COUNT(DISTINCT u.id) AS count FROM mail m LEFT JOIN users u ON m.sender = u.id WHERE u.idcompany = :companyId AND m.recipient = 1 AND m.view_status = 0");
foreach ($messagesGroupedByCompany as $companyId => $companyMessages) {
    $newMessagesByCompanyQuery->execute([':companyId' => $companyId]);
    $messagesGroupedByCompany[$companyId]['newMessages'] = $newMessagesByCompanyQuery->fetch(PDO::FETCH_ASSOC)['count'];
}

$userList = [];
$onlineUsersList = [];
