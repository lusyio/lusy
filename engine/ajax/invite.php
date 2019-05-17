<?php
global $pdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'deleteInvite') {
    $inviteId = filter_var($_POST['inviteId'], FILTER_SANITIZE_NUMBER_INT);
    deleteInvite($inviteId);
}
if ($_POST['module'] == 'createInvite' && isset($_POST['invitee-mail']) && isset($_POST['invitee-position'])) {
    $inviteeMail = filter_var($_POST['invitee-mail'], FILTER_SANITIZE_STRING);
    $inviteePosition = filter_var($_POST['invitee-position'], FILTER_SANITIZE_STRING);
    $inviteId = createInvite($inviteeMail, $inviteePosition);
    $invite = readInvite($inviteId);
    echo json_encode($invite);
}