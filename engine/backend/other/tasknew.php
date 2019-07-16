<?php

require_once __ROOT__ . '/engine/backend/other/tasks.php';

global $id;
global $tariff;
global $cometHash;
global $cometTrackChannelName;
global $roleu;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

$remainingLimits = getRemainingLimits();
$isTaskCreateDisabled = $remainingLimits['tasks'] <= 0;
$emptySpace = $remainingLimits['space'];