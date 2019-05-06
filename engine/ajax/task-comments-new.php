<?php 
$idtask = $_POST['it'];	
$text = $_POST['text'];

$datetime = date("Y-m-d H:i:s");
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
$sql->execute(array(':comment' => $text, ':status' => 'comment', ':iduser' => $id, ':idtask' => $idtask, 'view' => 0, ':datetime' => $datetime));
$idcomment = $pdo->lastInsertId();

if (count($_FILES) > 0) {
    uploadAttachedFiles('comment', $idcomment);
}
?>