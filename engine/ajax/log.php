<?php
global $pdo;
global $id;

require_once __ROOT__ . '/engine/backend/functions/log-functions.php';
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';


if ($_POST['module'] == 'markAsRead') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    markAsRead($eventId);
    echo json_encode(countTopsidebar());
}

if ($_POST['module'] == 'getEvent') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    $events = getEventByIdForUser($eventId);
    prepareEvents($events);
    renderEvent(array_pop($events));
}
