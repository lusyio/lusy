<?php

global $id;
global $tariff;
global $cometHash;
global $cometTrackChannelName;

$remainingLimits = getRemainingLimits();
$isTaskCreateDisabled = $remainingLimits['tasks'] <= 0;
$emptySpace = $remainingLimits['space'];
