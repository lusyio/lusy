<?php

require_once 'engine/backend/functions/reg-functions.php';
require_once 'engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'joinUser') {
    $inviteCode = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteeLogin = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteeName = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteeSurname = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteePassword = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteeMail = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);

    $inviteData = readInviteByCode($inviteCode);
    if (!$inviteData || $inviteData['status']) {
        die("Invite doesnt't exist or expired");
    }
    $newUserId = addUser($inviteeLogin, $inviteePassword, $inviteeMail, $inviteData['company_name'], $inviteData['invitee_position']);
    updateInvite($inviteData['invite_id'], $newUserId);
}