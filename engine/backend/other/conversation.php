<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;
global $tariff;

require_once 'engine/backend/functions/mail-functions.php';

$recipientId = filter_var($_GET['mail'], FILTER_SANITIZE_NUMBER_INT);

$messages = getMessages($id, $recipientId);
setMessagesViewStatus($id, $recipientId);

$onlineUsersQuery = $cometPdo->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
$onlineUsersQuery->execute(array(':channelName' => getCometTrackChannelName()));
$onlineUsers = $onlineUsersQuery ->fetchAll(PDO::FETCH_ASSOC);
$onlineUsersList = array_column($onlineUsers, 'user_id');
