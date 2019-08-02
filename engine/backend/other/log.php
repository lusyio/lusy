<?php
global $id;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $_buttonLogShowAll;
global $_buttonLogShowNew;
global $_tasks;
global $_comments;
global $supportCometHash;

require_once __ROOT__ . '/engine/backend/functions/log-functions.php';

$events = getEventsForUser(0, 7);
prepareEvents($events);
