<?php

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

global $pdo;
global $datetime;
global $id;
global $idc;
global $cometHash;
global $cometPdo;
global $cometTrackChannelName;
$lastChatMessage = lastChatMessage();
$newChatMessages = numberOfNewChatMessages();
$messages = DB('*','mail','sender = '.$id.' or recipient = '.$id. ' ORDER BY `datetime` DESC');
$userList = DB('id, name, surname', 'users', 'idcompany = '. $idc . ' AND id !=' . $id);
$onlineUsersQuery = $cometPdo->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
$onlineUsersQuery->execute(array(':channelName' => getCometTrackChannelName()));
$onlineUsers = $onlineUsersQuery ->fetchAll(PDO::FETCH_ASSOC);
$onlineUsersList = array_column($onlineUsers, 'user_id');

$dialog = [];

foreach ($messages as $n) {
    if (in_array($n['sender'], $dialog)) {
    } else {
        if ($n['sender'] != $id) {
            $dialog[] = $n['sender'];
        }
    }
    if (in_array($n['recipient'], $dialog)) {
    } else {
        if ($n['recipient'] != $id) {
            $dialog[] = $n['recipient'];
        }
    }
}

