<?php

global $id;
global $idc;
global $pdo;
global $cometTrackChannelName;
global $roleu;

if ($roleu != 'ceo') {
    header('location:/company/');
    ob_flush();
    die;
}

if (isset($_POST['position'])) {
    $inviteePosition = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
    if (isset($_POST['invitee-mail'])) {
        $inviteeMail = filter_var($_POST['invitee-mail'], FILTER_SANITIZE_STRING);
    } else {
        $inviteeMail = '';
    }
    createInvite($inviteeMail, $inviteePosition);
}

$invitesQuery = $pdo->prepare('SELECT invite_id, code, invite_date, status, email, invitee_position FROM invitations WHERE company_id=:companyId ORDER BY invite_date DESC');
$invitesQuery->execute(array(':companyId' => $idc));
$invites = $invitesQuery->fetchAll(PDO::FETCH_ASSOC);
