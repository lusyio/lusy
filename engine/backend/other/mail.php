<?php
global $pdo;
global $datetime;
global $id;
global $idc;



if (!empty($_POST['mes']) and !empty($_POST['recipient'])) {
		$dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
 		$sql = $pdo->prepare($dbh);
 		$sql->execute(array('mes' => $_POST['mes'], 'sender' => $id, 'recipient' => $_POST['recipient'], 'datetime' => $datetime));	
 		
 		if ($sql) {
	 		echo 'Отправлено';
 		}
}



$messages = DB('*','mail','sender = '.$id.' or recipient = '.$id);

$dialog = [];

foreach ($messages as $n) {
	if (in_array($n['sender'], $dialog)) { } else { 
		if ($n['sender'] != $id) { $dialog[] = $n['sender']; }
	}
	if (in_array($n['recipient'], $dialog)) { } else {
		if ($n['recipient'] != $id) { $dialog[] = $n['recipient']; }
	 }
}