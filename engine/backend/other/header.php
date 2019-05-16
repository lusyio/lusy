<?php
require_once 'engine/backend/functions/login-functions.php';
require_once 'engine/backend/functions/common-functions.php';
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace('/', '', $url);
	if (empty($_SESSION['auth']) && !empty($_COOKIE['token'])) {
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
    }
	if (!empty($_SESSION['auth'])) {

        // скачивание прикрепленного файла
        if (!empty($_GET['file'])) {
            $file = 'upload/files/' . $_GET['file'];
            if (file_exists($file)) {
                $fileName = DBOnce('file_name', 'uploads', 'file_path ="' . $file . '"');
                //header('Content-type: application/octet-stream');
                if ($fileName == '') {
                    die ('file not found in db');
                }
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                readfile($file);
                exit();
            } else {
                header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
                exit();
            }
        }

        include 'engine/backend/main/main.php';
			if (!empty($_GET['task']) or !empty($_GET['tasks'])) {
				$title = 'Задача';
			}
			if (!empty($_GET['mail']))
            {
                $recipientIdc = DBOnce('idcompany', 'users', 'id=' . $_GET['mail']);
                if ($idc == $recipientIdc || $id == $_GET['mail']) {
                    $title = 'Сообщения';
                } else {
                    header('location: /mail/');
                    die();
                }
            }
			if (!empty($_GET['profile'])) {
				$title = DBOnce('name','users','id='.$_GET["profile"]) . ' ' . DBOnce('surname','users','id='.$_GET["profile"]);
			}
			if (!empty($_GET['restore']) && !empty($_GET['code'])) {
			    $title = "Восстановление пароля"; //TODO Add locales
            }
			if (!empty($_GET['activate']) && !empty($_GET['code'])) {
			    $title = "Активация аккаунта"; //TODO Add locales
            }
			if(empty($title)) {
				if(empty($url)) {
				$title = $GLOBALS["_main"];
				} else {
				$title = $GLOBALS["_$url"];
				}
			}
		} else {
	    if (preg_match('~^restore~', $url)) {

        } else if (preg_match('~^activate~', $url)) {

        } else if (preg_match('~^join~', $url)) {

        } else if (in_array($url, $pages)) {
	        if ($url == 'reg') {

            } else if ($url != 'login') {
                header('location: /login/');
                die();
			}
		} else {
            header('location: /login/');
            die();
		}
	}