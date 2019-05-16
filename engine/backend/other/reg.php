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
            addUser($login, $password, $companyId, 'ceo');
            $_SESSION['login'] = $login;
            $_SESSION['password'] = $password;
            header('location: /login/');
            ob_flush();
            die;
        }
    } else {
        $regErrors[] = 'User with this e-mail has already exists';

    }
}
