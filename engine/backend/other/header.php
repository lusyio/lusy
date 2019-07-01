<?php
require_once 'engine/backend/functions/login-functions.php';
require_once 'engine/backend/functions/common-functions.php';
$url = $_SERVER['REQUEST_URI'];
$urlFirstPart = preg_split('~/~',trim($url, '/'))[0];
$url = str_replace('/', '', $url);
$isAuthorized = authorize();
$onlyForNonAuthorizedPages = ['reg', 'login', 'restore', 'join'];

if ($isAuthorized) {
    if (in_array($urlFirstPart, $onlyForNonAuthorizedPages)) {
        ob_clean();
        header('location: /');
        exit;
    }
    // Устанавливаем часовой пояс компании
    $companyTimeZone = DBOnce('timezone', 'company', 'id=' . $idc);
    date_default_timezone_set($companyTimeZone);
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
            header('Content-Disposition: inline; filename="' . $fileName . '"');
            if (mime_content_type($file)) {
                header('Content-Type: ' . mime_content_type($file));
            }
            readfile($file);
            exit();
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            exit();
        }
    }
    // загрузка аватарки
    if (isset($_GET['avatar']) && !empty($_GET['name'])) {
        if (empty($_SESSION['idc'])) {
            $_SESSION['idc'] = DBOnce('idcompany', 'users', 'id="' . $_SESSION['id'] . '"');
        }
        if ($_GET['avatar'] == '0' || $_GET['avatar'] == $_SESSION['idc']) {
            $file = 'upload/avatar/' . $_GET['avatar'] . '/' . $_GET['name'] . '.jpg';
            $last_modified_time = filemtime($file);
            $etag = md5_file($file);
            ob_clean();
            header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT");
            header("Etag: $etag");
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time || @trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
                header("HTTP/1.1 304 Not Modified");
                exit;
            } else {
                header('Content-type: image/jpeg');
                header("Cache-Control: private, max-age=2592000");
                readfile($file);
                ob_flush();
                die();
            }
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            die();
        }
    }

    $isCompanyActivated = (boolean) DBOnce('activated', 'company', 'id=' . $idc);

    include 'engine/backend/main/main.php';
    $cometTrackChannelName = getCometTrackChannelName();

    if (!empty($_GET['task']) or !empty($_GET['tasks'])) {
        $title = 'Задача';
    }
    if (!empty($_GET['folder']) && $_GET['folder'] == 'chat') {
        $title = 'Чат компании';
    }
    if (!empty($_GET['mail'])) {
        $recipientIdc = DBOnce('idcompany', 'users', 'id=' . $_GET['mail']);
        if ($idc == $recipientIdc || $id == $_GET['mail']) {
            $title = 'Диалоги';
        } else {
            header('location: /mail/');
            die();
        }
    }
    if (!empty($_GET['profile'])) {
        $title = DBOnce('name', 'users', 'id=' . $_GET["profile"]) . ' ' . DBOnce('surname', 'users', 'id=' . $_GET["profile"]);
    }
    require_once 'engine/backend/functions/achievement-functions.php';
    checkAchievements($id);
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

//здесь определить язык пользователя по данным из браузера
$langc = 'ru';
include 'engine/backend/lang/'.$langc.'.php';


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

