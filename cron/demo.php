<?php
		  	ini_set('error_reporting', E_ALL);
		  	ini_set('display_errors', 1);
	  		ini_set('display_startup_errors', 1);
			include '../engine/conf.php'; // подключаем базу данных
			
			$what = ['task','comment'];
			$whattodo = $what[array_rand($what)];
			
			if ($whattodo == 'task') {
			

			$names = ['Написать отчет','Создать лендинг','Добавить текст на сайт','Добавить новость про семинар на сайте','Написать программу вебинара','Отправить рассылку по вебинару','Собрать отзывы участников','Сделать анонс на сайте','Заказать банер на форуме','Настроить оборудование'];
			$data = date("Y-m-d H:i:s");
			$newdata = date('Y-m-d H:i:s', strtotime("+3 days"));
			// создаем первую задачу - пригласить сотрудников
			$sql = $pdo->prepare("INSERT INTO `tasks` SET `name` = :name, `description` = :description, `datecreate` = '".$data."', `datedone` = '".$newdata."', `status` = 'new', `manager` = '2', `worker` = '4', `idcompany` = '2'");
			$name = $names[array_rand($names)];
			$description = 'Здесь было НЛО и оставило описание';
			$sql->execute(array('name' => $name, 'description' => $description));
			$idtask = $pdo->lastInsertId();
			
			// создаем запись в log о создании задачи
			$intolog = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `task` = :idtask, `sender` = '2', `recipient` = '4', `idcompany` = '2', `datetime` = :datetime");
			$action = 'createtask';
			$intolog->execute(array('action' => $action, 'idtask' => $idtask, 'datetime' => $data));
			
			} else {
				
			$sql = 'SELECT id FROM `tasks` where worker = "4" and status = "new" ORDER BY RAND() LIMIT 1';
			$row = $pdo->query($sql);
			// Перебор и вывод результатов
			$result = $row->fetch();
			$idrandomtask = $result[0];
			
			$names = ['Напиши мне отчет по этой задаче','Создай лендинг','Добавь текст на сайт','Добавь новость про семинар на сайте','Напиши программу вебинара','Отправь рассылку по вебинару','Собери отзывы участников','Сделай анонс на сайте','Закажи банер на форуме','Настрой оборудование, чтобы все работало как надо в нужный момент'];
			$data = date("Y-m-d H:i:s");
			$newdata = date('Y-m-d H:i:s', strtotime("+3 days"));
			// создаем первую задачу - пригласить сотрудников
			$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :comment, `iduser` = '2', `idtask` = '".$idrandomtask."', `datetime` = '".$data."'");
			$name = $names[array_rand($names)];
			$sql->execute(array('comment' => $name));
			$idtask = $pdo->lastInsertId();
			
			// создаем запись в log о создании коммента
			$intolog = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `task` = :idtask, `comment` = :comment, `sender` = '2', `recipient` = '4', `idcompany` = '2', `datetime` = :datetime");
			$action = 'comment';
			$intolog->execute(array('action' => $action, 'idtask' => $idrandomtask, 'comment' => $idtask, 'datetime' => $data));
			
			}
			
			// закрываем соединение с бд
			$pdo = NULL;
			echo $whattodo;
?>
<div style="width: 100%;"><center><a href="http://dev.richbee.ru/demo.php" style=" text-align: center; font-size: 12em;">Обновить</a></center></div>