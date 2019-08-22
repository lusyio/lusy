<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;
global $tariff;
global $roleu;
global $supportCometHash;

$tryPremiumLimits = getFreePremiumLimits($idc);

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

$recipientId = filter_var($_GET['mail'], FILTER_SANITIZE_NUMBER_INT);
if ($recipientId == 1) {
    $supportDialog = true;
} else {
    $supportDialog = false;
}

$messages = getMessages($id, $recipientId);
setMessagesViewStatus($id, $recipientId);

$onlineUsersList = $cometPdo->getOnlineUsers(getCometTrackChannelName());

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];
