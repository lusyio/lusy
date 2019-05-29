<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once 'engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'deleteInvite') {
    $inviteId = filter_var($_POST['inviteId'], FILTER_SANITIZE_NUMBER_INT);
    deleteInvite($inviteId);
}
if ($_POST['module'] == 'createInvite' && isset($_POST['invitee-mail'])  && $roleu == 'ceo') {
    $inviteeMail = filter_var($_POST['invitee-mail'], FILTER_SANITIZE_STRING);
    $inviteePosition = 'worker';
    $inviteId = createInvite($inviteeMail, $inviteePosition);
    $invite = readInvite($inviteId);
    echo json_encode($invite);
}