<?php
$lang = 'ru';
require_once __ROOT__ . '/engine/backend/functions/login-functions.php';
require_once __ROOT__ . '/engine/backend/lang/' . $lang . '.php';
$loginError = false;
if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    header('location: /');
}
if (isset($_SESSION['login']) && isset($_SESSION['password'])) { //автоматический вход после регистрации
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
} elseif (!empty($_POST['login']) and !empty($_POST['password'])) {
    $login = trim($_POST['login']);
    $login = filter_var($login, FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
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
                $loginError = true;
            }
        } else {
            $loginError = true;
        }
    } else {
        $loginError = true;
    }
}


