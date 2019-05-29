<?php
global $pdo;
if (isset($_GET['activate']) && isset($_GET['code'])) {
    $companyId = filter_var($_GET['activate'], FILTER_SANITIZE_NUMBER_INT);
    $activateCode = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
    if (DBOnce('count(*)', 'company_activation', 'company_id=' . $companyId . ' AND code=\'' . $activateCode. '\'')) {

        $updateActivationQuery = $pdo->prepare('UPDATE company SET activated = :activated WHERE id = :companyId');
        $result = $updateActivationQuery->execute(array(':activated' => 1, ':companyId' => $companyId));
        $removeActivationCodeQuery = $pdo->prepare('DELETE FROM company_activation WHERE company_id=:companyId AND code=:code');
        $removeActivationCodeQuery->execute(array(':companyId' => $companyId, ':code' => $activateCode));
        if ($result) {
            $companyMail = DBOnce('email', 'users', 'idcompany=' . $companyId . ' AND role=\'ceo\'');
            require_once 'engine/phpmailer/LusyMailer.php';
            $mail = new \PHPMailer\PHPMailer\LusyMailer();
            $mail->addAddress($companyMail);
            $mail->isHTML();
            $mail->Subject = "Добро пожаловать в Lusy.io";
            $args = [];
            $mail->setMessageContent('company-welcome', $args);
            $mail->send();

            header('location: /login/');
        }
    }
}