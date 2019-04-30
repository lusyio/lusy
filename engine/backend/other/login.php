<?php
	if (!empty($_POST['login']) and !empty($_POST['password']) and !empty($_POST['idcompany'])) {
		
		
		$login = $_POST['login'];
		$password = md5($_POST['password']);
		$idcompany = $_POST['idcompany'];
		
		$idc = DBOnce('id','company','idcompany="'.$idcompany.'"');
		
		if (!empty($idc)) {
			
			$id = DBOnce('id','users','login="'.$login.'" and idcompany= "'.$idc.'"');
			$hash = DBOnce('password','users','login="'.$login.'" and idcompany= "'.$idc.'"');
			
			if (!empty($id)) {
				// Проверяем соответствие хеша из базы введенному паролю
				if (password_verify($_POST['password'], $hash)) {
					$_SESSION['auth'] = true;
					$_SESSION['id'] = $id;
					$_SESSION['idcompany'] = $idc;
                    header('location: /');
                    ob_end_flush();
                    die();
				} else {
					echo 'Неверные данные';
				}
			} else {
				echo 'Такого логина в данной компании нет';
			}
			
			
		}	else {
			echo 'Неверно указаны данные';
		}
	}