<?php

// отправка на проеврку

if($_POST['module'] == 'sendonreview') {
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "pending" WHERE id='.$idtask);
	$sql->execute();

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :report, `iduser` = :iduser, `idtask` = :idtask, `status` = 'report', `view`=0 ,`datetime` = :datetime");
	$sql->execute(array('report' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$commentId = $pdo->lastInsertId();

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
    }
}

if($_POST['module'] == 'sendpostpone') {
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);
	$datepostpone = filter_var($_POST['datepostpone'],FILTER_SANITIZE_SPECIAL_CHARS);
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
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
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "done", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));

	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn') {
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$datepostpone = filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS);
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);

	$text .= "\nНовый срок: " . date("d.m", strtotime($datepostpone));

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "returned", `view` = 0, `datepostpone` = :datepostpone WHERE id= :idtask');
	$sql->execute(array('datepostpone' => $datepostpone, 'idtask' => $idtask));

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'returned', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$commentId = $pdo->lastInsertId();

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
	}
}

// Кнопка В работу для worker'a

if($_POST['module'] == 'inwork') {
	// $report = $_POST['report'];
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "new" WHERE id='.$idtask);
	$sql->execute();

	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
}

// создание новой задачи

if($_POST['module'] == 'createTask') {
	$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	$description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
	$datedone = filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS);
	$worker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
	$dbh = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status,  manager, worker, idcompany, report, view) VALUES (:name, :description,'".$now."', :datedone, NULL, 'new', '".$id."', :worker, '".$idc."', :description, '0') ";
	$sql = $pdo->prepare($dbh);
	$sql->execute(array('name' => $name, 'description' => $description, 'worker' => $worker, 'datedone' => $datedone));

	if ($sql) {
		$idtask = $pdo->lastInsertId();
		if (!empty($idtask)) {
			echo $idtask;
		}
	}

    if (count($_FILES) > 0) {
        uploadAttachedFiles('task', $idtask);
    }
}

// отмена задачи

if($_POST['module'] == 'cancelTask') {
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "canceled", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));
	echo 'success';
}

//отклонение запроса на перенос срока

if ($_POST['module'] == 'cancelDate') {
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = null WHERE id=" . $idtask); //TODO нужно разобраться со статусами
	$sql->execute();
	$text = "Перенос отклонен";
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

//одобрение запроса на перенос срока

if ($_POST['module'] == 'confirmDate') {
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$statusWithDate = DBOnce('status', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
	$datepostpone = preg_split('~:~', $statusWithDate)[1];
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = :datepostpone WHERE id=" . $idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Перенос одобрен. Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

if ($_POST['module'] == 'sendDate') {
	$datepostpone = filter_var($_POST['sendDate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
	$sql = $pdo->prepare('UPDATE `tasks` SET `datepostpone` = :datepostpone, `view` = 0 WHERE id='.$idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
}

