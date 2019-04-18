<?php


// отправка на проеврку

if($_POST['module'] == 'sendonreview') {
	$report = $_POST['report'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "pending", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $report));
	
	if ($sql) {
		echo '<p>Успешно</p>';
	}
}

if($_POST['module'] == 'sendpostpone') {
	$datepostpone = $_POST['datepostpone'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "postpone", `datepostpone` = :datepostpone WHERE id='.$idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	
	if ($sql) {
		echo '<p>Успешно</p>';
	}
}



// Кнопка принять для worker'a

if($_POST['module'] == 'workdone') {
	// $report = $_POST['report'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "done" WHERE id='.$idtask);
	$sql->execute();
	
	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn') {
	// $report = $_POST['report'];
	$idtask = $_POST['it'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "returned" WHERE id='.$idtask);
	$sql->execute();
	
	// if ($sql) {
	// 	echo '<p>Успешно</p>';
	// }
	// var_dump($sql);
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
	$dbh = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status,  manager, worker, idcompany, report, view) VALUES (:name, :description,'".$now."', :datedone, '".$now."', 'new', '".$id."', :worker, '".$idc."', :description, '0') ";
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
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "canceled" WHERE id='.$idtask);
	$sql->execute();
}
?>

