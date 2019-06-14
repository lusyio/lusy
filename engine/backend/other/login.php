<?php
$lang = 'ru';
require_once 'engine/backend/functions/login-functions.php';
require_once 'engine/backend/lang/' . $lang . '.php';

if (!empty($_POST['login']) and !empty($_POST['password'])) {
    $login = trim(filter_var($_POST['login']), FILTER_SANITIZE_STRING);
    $password = trim(filter_var($_POST['password']), FILTER_SANITIZE_STRING);
} elseif (isset($_SESSION['login']) && isset($_SESSION['password'])) { //автоматический вход после регистрации
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    unset($_SESSION['login']);
    unset($_SESSION['password']);
}
if (isset($login) && isset($password)) {
    $idc = DBOnce('idcompany', 'users', 'email="' . mb_strtolower($login) . '"');
    $timestamp = time();
    if (!empty($idc)) {
        $id = DBOnce('id', 'users', 'email="' . mb_strtolower($login) . '"');
        $hash = DBOnce('password', 'users', 'email="' . mb_strtolower($login) . '"');
        $isFired = DBOnce('is_fired', 'users', 'email="' . mb_strtolower($login) . '"');
        if (!empty($id)) {
            // Проверяем соответствие хеша из базы введенному паролю
            if (password_verify($password, $hash) && !$isFired) {
                $_SESSION['auth'] = true;
                $_SESSION['id'] = $id;
                $_SESSION['idcompany'] = DBOnce('idcompany', 'users', 'email="' . mb_strtolower($login) . '"');

                removeExcessiveSessionsIfExists($id);
                $sessionId = createSession($id, $timestamp);
                setcookie('token', createCookieString($sessionId, $id, $timestamp), time() + 60 * 60 * 24 * 30, '/', '');
                header('location: /');
                ob_end_flush();
                die();
            } else {
                echo 'Неверные данные';
            }
        } else {
            echo 'Такого логина в данной компании нет';
        }
    } else {
        echo 'Неверно указаны данные';
    }
}


