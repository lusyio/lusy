<?php
require_once 'engine/backend/functions/login-functions.php';
if (!empty($_COOKIE['token'])) {
    $sessionCookie = parseCookie($_COOKIE['token']);
    if (!$sessionCookie) {
        setcookie('token', null, -1, '/');
        header('location: /login/');
        ob_end_flush();
    }
    if (!isCookieExistAndValidByTimestamp($sessionCookie)) {
        removeSessions($sessionCookie['sid']);
        setcookie('token', null, -1, '/');
        header('location: /login/');
        ob_end_flush();
        die();
    }
    $_SESSION['auth'] = true;
    $_SESSION['id'] = $sessionCookie['uid'];
    $id = $sessionCookie['uid'];
    $idc = DBOnce('idcompany', 'users', 'id="' . $id . '"');
    $timestamp = time();
    updateCookieTime($sessionCookie, $timestamp);
    setcookie('token', createCookieString($sessionCookie['sid'], $sessionCookie['uid'], $timestamp), time() + 60 * 60 * 24 * 30, '/');
    header('location: /');
    ob_end_flush();
    die();
} else {
    if (!empty($_POST['login']) and !empty($_POST['password']) and !empty($_POST['idcompany'])) {

        $login = filter_var($_POST['login'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $idcompany = filter_var($_POST['idcompany'], FILTER_SANITIZE_STRING);

    } elseif (isset($_SESSION['login']) && isset($_SESSION['password']) && isset($_SESSION['idcompany'])) {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $idcompany = $_SESSION['idcompany'];
        unset($_SESSION['login']);
        unset($_SESSION['password']);
        unset($_SESSION['idcompany']);
    }
    if (isset($login) && isset($password) && isset($idcompany)) {
        $idc = DBOnce('id', 'company', 'idcompany="' . $idcompany . '"');
        $timestamp = time();
        if (!empty($idc)) {
            $id = DBOnce('id', 'users', 'login="' . $login . '" and idcompany= "' . $idc . '"');
            $hash = DBOnce('password', 'users', 'login="' . $login . '" and idcompany= "' . $idc . '"');
            if (!empty($id)) {
                // Проверяем соответствие хеша из базы введенному паролю
                if (password_verify($password, $hash)) {
                    $_SESSION['auth'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['idcompany'] = $idc;

                    removeExcessiveSessionsIfExists($id);
                    $sessionId = createSession($id, $timestamp);
                    setcookie('token', createCookieString($sessionId, $id, $timestamp), time() + 60 * 60 * 24 * 30, '/');
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
}

