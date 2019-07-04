<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;
global $roleu;
global $tariff;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

$isCeoAndInChat = $roleu == 'ceo';

$messages = getChatMessages();

$onlineUsersQuery = $cometPdo->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
$onlineUsersQuery->execute(array(':channelName' => getCometTrackChannelName()));
$onlineUsers = $onlineUsersQuery ->fetchAll(PDO::FETCH_ASSOC);
$onlineUsersList = array_column($onlineUsers, 'user_id');
markChatAsRead();

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];
