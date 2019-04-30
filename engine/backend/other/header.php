<?php
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace('/', '', $url);
	if (!empty($_COOKIE['token'])) {

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
			if(empty($title)) {
				if(empty($url)) {
				$title = $GLOBALS["_main"];
				} else {
				$title = $GLOBALS["_$url"];
				}
			}
		} else {
		if (in_array($url, $pages)) { 	
			if ($url != 'login') {
                header('location: /login/');
                die();
			}
		} else {
            header('location: /login/');
            die();
		}
	}