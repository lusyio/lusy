<?php

global $pdo;

require_once __ROOT__ . '/engine/backend/functions/reg-functions.php';

$login = '';
$userLanguage = getUserLanguage();
$regErrors = [];
if (isset($_POST['email'])) {
    $companyTimeZone = 'Europe/Moscow';
    $companyLanguage = 'ru';
    $login = trim($_POST['email']);
    $login = filter_var($login, FILTER_SANITIZE_EMAIL);
    $companyName = preg_split('~@~', $login)[0];
    // Генерация пароля
    $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
    $digits = '123456789';
    $symbols = '-_.';
    $password = '';
    $password .= $digits[rand(0,mb_strlen($digits) - 1)];
    $password .= $symbols[rand(0,mb_strlen($symbols) - 1)];
    for ($i=0;$i<6;$i++) {
        $password .= $chars[rand(0,mb_strlen($chars) - 1)];
    }
    do {
        $password = str_shuffle($password);
    } while (!preg_match('~^[a-zA-Z0-9].+[a-zA-Z0-9]+$~', $password));
    $isLoginGood = !isEmailExist($login);
    if ($isLoginGood) {
        $companyId = addCompany($companyName, $companyLanguage, $companyTimeZone);
        if ($companyId) {
            $ceoId = addUser($login, $password, $companyId, 'ceo');
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['companyName'] = $companyName;
            $_SESSION['isFirstLogin'] = true;
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
