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

$lastChatMessage = lastChatMessage();
$newChatMessages = numberOfNewChatMessages();
$messages = DB('*','mail','sender = '.$id.' or recipient = '.$id. ' ORDER BY `datetime` DESC');
$userList = DB('id, name, surname', 'users', 'idcompany = '. $idc . ' AND id !=' . $id);
$onlineUsersList = $cometPdo->getOnlineUsers(getCometTrackChannelName());

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

if ($idc == 1) {
    $newSupportMessages = DBOnce('COUNT(DISTINCT sender)', 'mail', 'recipient = 1 AND view_status = 0');
}

