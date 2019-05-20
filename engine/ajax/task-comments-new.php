<?php 
$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);

$datetime = date("Y-m-d H:i:s");
setcookie($idtask, strtotime($datetime), time() + 60 * 60 * 24 * 30, '/');
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
$sql->execute(array(':comment' => $text, ':status' => 'comment', ':iduser' => $id, ':idtask' => $idtask, 'view' => 0, ':datetime' => $datetime));
$idcomment = $pdo->lastInsertId();

if (count($_FILES) > 0) {
    uploadAttachedFiles('comment', $idcomment);
}

addMassEvent('comment', $idtask, $idcomment);
?>