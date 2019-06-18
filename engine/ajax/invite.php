<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once 'engine/backend/functions/invite-functions.php';
require_once 'engine/backend/functions/reg-functions.php';

if ($_POST['module'] == 'deleteInvite') {
    $inviteId = filter_var($_POST['inviteId'], FILTER_SANITIZE_NUMBER_INT);
    deleteInvite($inviteId);
}
if ($_POST['module'] == 'createInvite' && isset($_POST['invitee-mail'])  && $roleu == 'ceo') {
    $inviteeMail = filter_var($_POST['invitee-mail'], FILTER_SANITIZE_STRING);
    if (isEmailExist($inviteeMail) || isEmailInvited($inviteeMail)) {
        echo 'emailexist';
        die;
    }
    $inviteePosition = 'worker';
    $inviteId = createInvite($inviteeMail, $inviteePosition);
    $invite = readInvite($inviteId);

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';
    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    try {
        $mail->addAddress($inviteeMail);
        $mail->isHTML();
        $companyName = DBOnce('idcompany', 'company', 'id=' . $idc);
        $mail->Subject = "Приглашение в Lusy.io от " . $companyName;
        $args = [
            'companyName' => $companyName,
            'inviteLink' => 'https://' . $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/',
        ];
        $mail->setMessageContent('user-invite', $args);
        $mail->send();
    } catch (Exception $e) {

    }

    echo json_encode($invite);
}