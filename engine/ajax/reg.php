<?php

require_once 'engine/backend/functions/common-functions.php';
require_once 'engine/backend/functions/reg-functions.php';
require_once 'engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'joinUser') {
    $inviteCode = filter_var($_POST['inviteCode'], FILTER_SANITIZE_STRING);
    $inviteeMail = filter_var($_POST['inviteeMail'], FILTER_SANITIZE_STRING);

    $inviteeName = filter_var($_POST['inviteeName'], FILTER_SANITIZE_STRING);
    $inviteeSurname = filter_var($_POST['inviteeSurname'], FILTER_SANITIZE_STRING);
    $inviteePassword = filter_var($_POST['inviteePassword'], FILTER_SANITIZE_STRING);

    $inviteData = readInviteByCode($inviteCode);
    if (!$inviteData || $inviteData['status']) {
        die("Invite doesnt't exist or expired");
    }
    $newUserId = addUser($inviteeMail, $inviteePassword, $inviteData['company_id'], $inviteData['invitee_position'], $inviteeName, $inviteeSurname);
    updateInvite($inviteData['invite_id'], $newUserId);
    $idc = $inviteData['company_id'];
    addMassSystemEvent('newUserRegistered', $newUserId);
    session_start();
    $_SESSION['login'] = $inviteeMail;
    $_SESSION['password'] = $inviteePassword;
    var_dump($_SESSION);
}