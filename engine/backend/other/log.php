<?php
global $id;
global $idc;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $_buttonLogShowAll;
global $_buttonLogShowNew;
global $_tasks;
global $_comments;
global $supportCometHash;

require_once __ROOT__ . '/engine/backend/functions/log-functions.php';
require_once __ROOT__ . '/engine/backend/classes/EventList.php';

$eventList = new EventList($id, $idc);
$eventList->setEventsPeriod(7);
$events = $eventList->getEvents();
prepareEvents($events);
