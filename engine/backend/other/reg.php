<?php

require_once 'engine/backend/functions/reg-functions.php';

global $pdo;
$userLanguage = getUserLanguage();
$regErrors = [];
if (isset($_POST['companyName']) && isset($_POST['email']) && isset($_POST['password'])) {
    if (isset($_POST['timezone'])) {
        $companyTimeZone = filter_var($_POST['timezone'], FILTER_SANITIZE_STRING);
    }else {
        $companyTimeZone = 'UTC';
    }
    if (isset($_POST['language'])) {
        if ($_POST['language'] == 'ru') {
            $companyLanguage = 'ru';
        } else if ($_POST['language'] == 'en') {
            $companyLanguage = 'en';
        } else {
            $companyLanguage = 'en';
        }
    } else {
        $companyLanguage = 'en';
    }
    $companyName = filter_var(trim($_POST['companyName']), FILTER_SANITIZE_STRING);
    $login = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);

    $isLoginGood = !isEmailExist($login);

    if ($isLoginGood) {
        $companyId = addCompany($companyName, $companyLanguage, $companyTimeZone);
        if ($companyId) {
            $ceoId = addUser($login, $password, $companyId, 'ceo');
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;

            addEvent('newcompany', '' , '$companyId' , $ceoId);

            $activationCode = createActivationCode($companyId);
            require_once 'engine/phpmailer/LusyMailer.php';
            require_once 'engine/phpmailer/Exception.php';
            $mail = new \PHPMailer\PHPMailer\LusyMailer();
            try {
                $mail->addAddress($login);
                $mail->isHTML();
                $mail->Subject = "Подтверждение e-mail";
                $args = [
                    'activationLink' => $_SERVER['HTTP_HOST'] . '/activate/' . $companyId . '/' . $activationCode . '/',
                ];
                $mail->setMessageContent('company-activation', $args);
                $mail->send();
            } catch (Exception $e) {

            }
            header('location: /login/');
            ob_flush();
            die;
        }
    } else {
        $regErrors[] = 'User with this e-mail already exists';

    }
}
