<?php
global $pdo;
global $id;
global $idc;
global $roleu;

require_once 'engine/backend/functions/company-functions.php';
require_once 'engine/backend/functions/reg-functions.php';

if ($_POST['module'] == 'fireUser' && $roleu == 'ceo') {
    $userToFireId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    fireUser($userToFireId);
}
if ($_POST['module'] == 'restoreUser' && $roleu == 'ceo') {
    $userToRestoreId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    restoreUser($userToRestoreId);
}
if ($_POST['module'] == 'sendActivation' && $roleu == 'ceo') {
    $activationCode = getActivationCode($idc);
    var_dump($activationCode);
    $ceoEmail = DBOnce('email', 'users', 'id = ' . $id);

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';
    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    try {
        $mail->addAddress($ceoEmail);
        $mail->isHTML();
        $mail->Subject = "Подтверждение e-mail";
        $args = [
            'activationLink' => 'https://' . $_SERVER['HTTP_HOST'] . '/activate/' . $idc . '/' . $activationCode . '/',
        ];
        $mail->setMessageContent('company-activation', $args);
        $mail->send();
    } catch (Exception $e) {

    }
}
