<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;
global $supportCometHash;
global $tariff;
global $roleu;

if ($idc != 1) {
    header('location: /mail/');
}

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

$recipientId = filter_var($_GET['support'], FILTER_SANITIZE_NUMBER_INT);
$supportDialog = false;
$messages = getMessages(1, $recipientId);
setMessagesViewStatus(1, $recipientId);

$onlineUsersList = [];

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];
