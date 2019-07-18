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
$messages = DB('*','mail','recipient = 1 ORDER BY `datetime` DESC');
$userList = [];

$onlineUsersList = [];

$dialog = [];
foreach ($messages as $n) {
    if (in_array($n['sender'], $dialog)) {
    } else {
        if ($n['sender'] != 1) {
            $dialog[] = $n['sender'];
        }
    }
    if (in_array($n['recipient'], $dialog)) {
    } else {
        if ($n['recipient'] != 1) {
            $dialog[] = $n['recipient'];
        }
    }
}
