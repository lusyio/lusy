<?php

require_once 'engine/backend/functions/reg-functions.php';

global $pdo;

$regErrors = [];
if (isset($_POST['companyName']) && isset($_POST['email'])&& isset($_POST['password'])) {
    $companyName = filter_var($_POST['companyName'], FILTER_SANITIZE_STRING);
    $login = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $isLoginGood = !isEmailExist($login);

    if ($isLoginGood) {
        $companyLanguage = getUserLanguage();
        $companyId = addCompany($companyName, $companyLanguage);
        if ($companyId) {
            $activationCode = createActivationCode($companyId);
            require_once 'engine/phpmailer/LusyMailer.php';
            $mail = new \PHPMailer\PHPMailer\LusyMailer();
            $mail->addAddress($login);
            $mail->isHTML();
            $mail->Subject = "Подтверждение e-mail";
            $args = [
                'activationLink' => $_SERVER['HTTP_HOST'] . '/activate/' . $companyId . '/' . $activationCode . '/',
            ];
            $mail->setMessageContent('company-activation', $args);
            $mail->send();

            addUser($login, $password, $companyId, 'ceo');
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            addMassSystemEvent('newCompanyRegistered', '' , $companyId);
            header('location: /login/');
            ob_flush();
            die;
        }
    } else {
        $regErrors[] = 'User with this e-mail already exists';

    }
}
