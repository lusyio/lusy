<?php

require_once 'engine/backend/functions/common-functions.php';
require_once 'engine/backend/functions/reg-functions.php';
require_once 'engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'joinUser') {
    $inviteCode = filter_var(trim($_POST['inviteCode']), FILTER_SANITIZE_STRING);
    $inviteeMail = filter_var(trim($_POST['inviteeMail']), FILTER_SANITIZE_STRING);
    $inviteeName = filter_var(trim($_POST['inviteeName']), FILTER_SANITIZE_STRING);
    $inviteeSurname = filter_var(trim($_POST['inviteeSurname']), FILTER_SANITIZE_STRING);
    $inviteePassword = filter_var(trim($_POST['inviteePassword']), FILTER_SANITIZE_STRING);

    $inviteData = readInviteByCode($inviteCode);
    if (!$inviteData || $inviteData['status']) {
        die("Invite doesnt't exist or expired");
    }
    if (isEmailExist($inviteeMail)) {
        die('User with this e-mail already exists');
    }
    $newUserId = addUser($inviteeMail, $inviteePassword, $inviteData['company_id'], $inviteData['invitee_position'], $inviteeName, $inviteeSurname);
    updateInvite($inviteData['invite_id'], $newUserId);

    require_once 'engine/phpmailer/LusyMailer.php';
    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    $mail->addAddress($inviteeMail);
    $mail->isHTML();
    $mail->Subject = "Добро пожаловать в Lusy.io";
    $companyName = DBOnce('idcompany', 'company', 'id='.$inviteData['company_id']);
    $args = [
        'companyName' => $companyName,
    ];
    $mail->setMessageContent('user-welcome', $args);
    $mail->send();

    session_start();
    $_SESSION['login'] = $inviteeMail;
    $_SESSION['password'] = $inviteePassword;
    addMassSystemEvent('newUserRegistered', $newUserId, $inviteData['company_id']);
}
