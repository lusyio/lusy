<?php
global $pdo;
global $id;

require_once 'engine/backend/functions/log-functions.php';


if ($_POST['module'] == 'markAsRead') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    markAsRead($eventId);
}

function markAsRead($eventId)
{
    global $id;
    global $pdo;
    $markQuery = $pdo->prepare('UPDATE events SET view_status = 1 WHERE event_id = :eventId AND recipient_id = :userId');
    $markQuery->execute(array(':eventId' => $eventId, ':userId' => $id));
}
if ($_POST['module'] == 'getEvent') {
    $eventId = filter_var($_POST['eventId'], FILTER_SANITIZE_NUMBER_INT);
    $events = getEventByIdForUser($eventId);
    prepareEvents($events);
    renderEvent(array_pop($events));
}