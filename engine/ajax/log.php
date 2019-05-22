<?php
global $pdo;
global $id;

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