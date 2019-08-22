<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
global $cometHash;
global $roleu;
global $tariff;
global $supportCometHash;

$tryPremiumLimits = getFreePremiumLimits($idc);

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

$isCeoAndInChat = $roleu == 'ceo';

$messages = getChatMessages();

$onlineUsersList = $cometPdo->getOnlineUsers(getCometTrackChannelName());
markChatAsRead();

$remainingLimits = getRemainingLimits();
$emptySpace = $remainingLimits['space'];
