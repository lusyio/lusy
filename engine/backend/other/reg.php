<?php

require_once 'engine/backend/functions/reg-functions.php';

global $pdo;

$regErrors = [];
if (isset($_POST['companyName']) &&isset($_POST['login']) &&isset($_POST['password']) &&isset($_POST['email'])) {
    $companyName = filter_var($_POST['companyName'], FILTER_SANITIZE_STRING);
    $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

    $isLoginGood = !isLoginExist($login);
    $isEmailGood = !isEmailExist($email);
    if (!$isLoginGood) {
        $regErrors[] = 'This login has already exists';
    }
    if (!$isEmailGood) {
        $regErrors[] = 'User with this e-mail has already exists';
    }
    if ($isLoginGood && $isEmailGood) {
        $companyLanguage = getUserLanguage();
        $companyId = addCompany($companyName, $companyLanguage);
        if ($companyId) {
            $activationCode = createActivationCode($companyId);
            addUser($login, $password, $email, $companyId);
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            $_SESSION['idcompany'] = $companyName;
            header('location: /login/');
            ob_flush();
            die;
        }
    }
}
