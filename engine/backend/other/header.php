<?php
require_once 'engine/backend/functions/login-functions.php';
require_once 'engine/backend/functions/common-functions.php';
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/', '', $url);
$isAuthorized = authorize();
$onlyForNonAuthorizedPages = ['reg', 'login', 'restore', 'join'];

if ($isAuthorized) {
    if (in_array($url, $onlyForNonAuthorizedPages)) {
        header('location: /');
        exit;
    }
    // обновляем время последнего посещения
    setLastVisit();
    // скачивание прикрепленного файла
    if (!empty($_GET['file'])) {
        $file = 'upload/files/' . $_GET['file'];
        if (file_exists($file)) {
            $fileName = DBOnce('file_name', 'uploads', 'file_path ="' . $file . '"');
            if ($fileName == '') {
                die ('file not found in db');
            }
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile($file);
            exit();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            exit();
        }
    }
    // загрузка аватарки
    if (!empty($_GET['avatar']) && !empty($_GET['name'])) {
        if (empty($_SESSION['idc'])) {
            $_SESSION['idc'] = DBOnce('idcompany', 'users', 'id="' . $_SESSION['id'] . '"');
        }
        if ($_GET['avatar'] == $_SESSION['idc']) {
            $file = 'upload/avatar/' . $_GET['avatar'] . '/' . $_GET['name'] . '.jpg';
            header('Content-type: image/jpg');
            readfile($file);
            die();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            die();
        }
    }

    include 'engine/backend/main/main.php';
    $cometTrackChannelName = getCometTrackChannelName();

    if (!empty($_GET['task']) or !empty($_GET['tasks'])) {
        $title = 'Задача';
    }
    if (!empty($_GET['mail'])) {
        $recipientIdc = DBOnce('idcompany', 'users', 'id=' . $_GET['mail']);
        if ($idc == $recipientIdc || $id == $_GET['mail']) {
            $title = 'Сообщения';
        } else {
            header('location: /mail/');
            die();
        }
    }
    if (!empty($_GET['profile'])) {
        $title = DBOnce('name', 'users', 'id=' . $_GET["profile"]) . ' ' . DBOnce('surname', 'users', 'id=' . $_GET["profile"]);
    }


} else {
    $publicPages = ['reg', 'login', 'restore', 'activate', 'join'];
    $canProceed = false;
    foreach ($publicPages as $page) {
        if (preg_match('~^' . $page . '~', $url)) {
            $canProceed = true;
            break;
        }
    }
    if (!$canProceed) {
        header('location: /login/');
        exit;
    }
}

if (!empty($_GET['restore']) && !empty($_GET['code'])) {
    $title = "Восстановление пароля"; //TODO Add locales
}
if (!empty($_GET['activate']) && !empty($_GET['code'])) {
    $title = "Активация аккаунта"; //TODO Add locales
}
if (empty($title)) {
    if (empty($url)) {
        $title = $GLOBALS["_main"];
    } else {
        $url = strtok($url, '?');
        if (empty($GLOBALS["_$url"])) {
            $title = '';
        } else {
            $title = $GLOBALS["_$url"];
        }
    }
}
