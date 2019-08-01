<?php

require_once __ROOT__ . '/engine/backend/functions/reg-functions.php';

$email = '';
if (!empty($_POST['email'])) {
    $email = $_POST['email'];
}
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
    $companyName = trim($_POST['companyName']);
    $companyName = filter_var($companyName, FILTER_SANITIZE_STRING);
    $login = trim($_POST['email']);
    $login = filter_var($login, FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $passwordRule = '~^[\w\~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/<>?]{6,64}$~';
    if (!preg_match($passwordRule, $password)) {
        $regErrors[] = 'Incorrect password format';
    } else {
        $isLoginGood = !isEmailExist($login);

        if ($isLoginGood) {
            $companyId = addCompany($companyName, $companyLanguage, $companyTimeZone);
            if ($companyId) {
                $ceoId = addUser($login, $password, $companyId, 'ceo');
                $_SESSION['login'] = $login;
                $_SESSION['password'] = $password;

                addEvent('newcompany', '', $companyId, $ceoId);
                createInitTask($ceoId, $companyId, true);
                addMailToQueue('sendActivationLink', [$companyId], $ceoId);
                header('location: /login/');
                ob_flush();
                die;
            }
        } else {
            $regErrors[] = 'User with this e-mail already exists';
        }
    }
}
