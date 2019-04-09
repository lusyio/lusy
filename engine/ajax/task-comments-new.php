<?php 
$idtask = $_POST['it'];	
$text = $_POST['text'];

$datetime = date("Y-m-d H:i:s");
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :comment, `iduser` = :iduser, `idtask` = :idtask, `datetime` = :datetime");
$sql->execute(array('comment' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
$idcomment = $pdo->lastInsertId();
			
if ($id != $worker) {
// создаем запись в log о создании коммента
$intolog = $pdo->prepare("INSERT INTO `log` SET `action` = :action, `task` = :idtask, `comment` = :comment, `sender` = :sender, `recipient` = :recipient, `idcompany` = :recipient, `datetime` = :datetime");
$action = 'comment';
$intolog->execute(array('action' => $action, 'idtask' => $idtask, 'comment' => $idcomment, 'sender' => $id, 'recipient' => $worker, 'idcompany' => $idc, 'datetime' => $datetime));
}
?>