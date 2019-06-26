<?php 
$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
$text = filter_var(trim($_POST['text']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

$datetime = time();
setcookie($idtask, $datetime, time() + 60 * 60 * 24 * 30, '/');
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
$sql->execute(array(':comment' => $text, ':status' => 'comment', ':iduser' => $id, ':idtask' => $idtask, 'view' => 0, ':datetime' => time()));
$idcomment = $pdo->lastInsertId();

if (count($_FILES) > 0) {
    uploadAttachedFiles('comment', $idcomment);
}

addCommentEvent($idtask, $idcomment);

?>