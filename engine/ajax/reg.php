<?php

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/reg-functions.php';
require_once __ROOT__ . '/engine/backend/functions/invite-functions.php';

if ($_POST['module'] == 'joinUser') {
    $inviteCode = filter_var(trim($_POST['inviteCode']), FILTER_SANITIZE_STRING);
    $inviteeMail = filter_var(trim($_POST['inviteeMail']), FILTER_SANITIZE_EMAIL);
    $inviteeName = filter_var(trim($_POST['inviteeName']), FILTER_SANITIZE_STRING);
    $inviteeSurname = filter_var(trim($_POST['inviteeSurname']), FILTER_SANITIZE_STRING);
    $inviteePassword = trim($_POST['inviteePassword']);
    $inviteePassword = filter_var($inviteePassword, FILTER_SANITIZE_STRING);
    $passwordRule = '~^[\w\~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/<>?]{6,64}$~';
    if (!preg_match($passwordRule, $inviteePassword)) {
        exit('Incorrect password');
    }

    $inviteData = readInviteByCode($inviteCode);
    if (!$inviteData || $inviteData['status']) {
        die("Invite doesnt't exist or expired");
    }
    if (isEmailExist($inviteeMail) || $inviteeMail == '') {
        die('User with this e-mail already exists');
    }
    $newUserId = addUser($inviteeMail, $inviteePassword, $inviteData['company_id'], $inviteData['invitee_position'], $inviteeName, $inviteeSurname);
    updateInvite($inviteData['invite_id'], $newUserId);

    @session_start();
    $_SESSION['login'] = $inviteeMail;
    $_SESSION['password'] = $inviteePassword;
    $idc = $inviteData['company_id'];
    addEvent('newuser', '', $newUserId);
    addEvent('userwelcome', '' , '' , $newUserId);

    createInitTask($newUserId, $idc);

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';
    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    try {
        $mail->addAddress($inviteeMail);
        $mail->isHTML();
        $mail->Subject = "Добро пожаловать в Lusy.io";
        $companyName = DBOnce('idcompany', 'company', 'id='.$inviteData['company_id']);
        $args = [
            'companyName' => $companyName,
        ];
        $mail->setMessageContent('user-welcome', $args);
        $mail->send();
    } catch (Exception $e) {

    }



}
