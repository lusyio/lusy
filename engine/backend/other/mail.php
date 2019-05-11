<?php

require_once 'engine/backend/functions/mail-functions.php';

global $pdo;
global $datetime;
global $id;
global $idc;
global $cometHash;

$messages = DB('*','mail','sender = '.$id.' or recipient = '.$id. ' ORDER BY `datetime` DESC');
$userList = DB('id, name, surname', 'users', 'idcompany = '. $idc . ' AND id !=' . $id);
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

