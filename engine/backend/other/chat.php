<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;

require_once 'engine/backend/functions/mail-functions.php';

$messages = getChatMessages();

$onlineUsersQuery = $cometPdo->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
$onlineUsersQuery->execute(array(':channelName' => getCometTrackChannelName()));
$onlineUsers = $onlineUsersQuery ->fetchAll(PDO::FETCH_ASSOC);
$onlineUsersList = array_column($onlineUsers, 'user_id');
markChatAsRead();
