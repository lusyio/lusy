<?php


// отправка на проеврку

if($_POST['module'] == 'sendonreview') {
	$text = $_POST['text'];
	$idtask = $_POST['it'];

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "pending" WHERE id='.$idtask);
	$sql->execute();

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :report, `iduser` = :iduser, `idtask` = :idtask, `status` = 'report', `view`=0 ,`datetime` = :datetime");
	$sql->execute(array('report' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$commentId = $pdo->lastInsertId();

	if (count($_FILES) > 0) {
		$dirName = 'upload/files/' . $idtask;
		if (!realpath($dirName)) {
			mkdir($dirName, 0777, true);
		}
		$text .= "\nПрикрепленные файлы";

		$sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType)');
		foreach ($_FILES as $file) {
			$fileName = basename($file['name']);
			$filePath = $dirName . '/'. $fileName;
			var_dump($filePath);
			var_dump($fileName);
			$sql->execute(array(':fileName' => $fileName, ':fileSize' => $file['size'], ':filePath' => $filePath, ':commentId' => $commentId, ':commentType' => 'comment'));
			move_uploaded_file($file['tmp_name'], $filePath);
		}
	}



	if ($sql) {
		echo '<p>Успешно</p>';
	}
}

if($_POST['module'] == 'sendpostpone') {
	$text = $_POST['text'];
	$datepostpone = $_POST['datepostpone'];
	$idtask = $_POST['it'];
	$status = 'request:' . $datepostpone;

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "postpone" WHERE id='.$idtask);
	$sql->execute();

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :report, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");
	$sql->execute(array('report' => $text, 'iduser' => $id, 'idtask' => $idtask, 'status' => $status, 'datetime' => $datetime));

	if ($sql) {
		echo '<p>Успешно</p>';
	}
}



// Кнопка принять для worker'a

if($_POST['module'] == 'workdone') {
	// $report = $_POST['report'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "done", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));
	
	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn') {
	$idtask = $_POST['it'];
	$datepostpone = $_POST['datepostpone'];
	$text = $_POST['text'];
	$text .= "\nНовый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "returned", `view` = 0, `datepostpone` = :datepostpone WHERE id= :idtask');
	$sql->execute(array('datepostpone' => $datepostpone, 'idtask' => $idtask));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'returned', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

// Кнопка В работу для worker'a

if($_POST['module'] == 'inwork') {
	// $report = $_POST['report'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "new" WHERE id='.$idtask);
	$sql->execute();
	
	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
}

// создание новой задачи

if($_POST['module'] == 'createTask') {
	$name = $_POST['name'];
	$description = $_POST['description'];
	$datedone = $_POST['datedone'];
	$worker = $_POST['worker'];
	$dbh = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status,  manager, worker, idcompany, report, view) VALUES (:name, :description,'".$now."', :datedone, NULL, 'new', '".$id."', :worker, '".$idc."', :description, '0') ";
	$sql = $pdo->prepare($dbh);
	$sql->execute(array('name' => $name, 'description' => $description, 'worker' => $worker, 'datedone' => $datedone));
	
	if ($sql) {
		$idtask = $pdo->lastInsertId();
		if (!empty($idtask)) {
			echo $idtask;
		}
	}
}

// отмена задачи

if($_POST['module'] == 'cancelTask') {
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "canceled", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));
	echo 'success';
}

//отклонение запроса на перенос срока

if ($_POST['module'] == 'cancelDate') {
	$idtask = $_POST['it'];
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = null WHERE id=" . $idtask); //TODO нужно разобраться со статусами
	$sql->execute();
	$text = "Перенос отклонен";
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

//одобрение запроса на перенос срока

if ($_POST['module'] == 'confirmDate') {
	$idtask = $_POST['it'];
	$statusWithDate = DBOnce('status', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
	$datepostpone = preg_split('~:~', $statusWithDate)[1];
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = :datepostpone WHERE id=" . $idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Перенос одобрен. Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

if ($_POST['module'] == 'sendDate') {
	$datepostpone = $_POST['sendDate'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `datepostpone` = :datepostpone, `view` = 0 WHERE id='.$idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}
?>

