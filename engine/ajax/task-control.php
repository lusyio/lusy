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
?>