<?php
global $pdo;
global $id;

require_once 'engine/backend/functions/log-functions.php';


if ($_POST['module'] == 'markAsRead') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    markAsRead($eventId);
}

if ($_POST['module'] == 'getEvent') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    $events = getEventByIdForUser($eventId);
    prepareEvents($events);
    renderEvent(array_pop($events));
}