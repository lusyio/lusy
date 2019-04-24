<?php
$idtask = $_POST['it'];
$countcomments = DBOnce('COUNT(*) as count', 'comments', 'idtask=' . $idtask);

if ($countcomments > 0) {
    include 'engine/ajax/frontend/comments-header.php';
    $comments = $pdo->prepare('SELECT id, iduser, comment, status, datetime FROM `comments` where idtask = :idtask ORDER BY datetime desc');
    $comments->execute(array(':idtask' => $idtask));
    $comments = $comments->fetchAll(PDO::FETCH_BOTH);

    foreach ($comments as $c) {
// получаем информацию о юзере
        $sql = 'SELECT name, surname, login FROM users where id = "' . $c['iduser'] . '" limit 1';
        $row = $pdo->query($sql);
        $result = $row->fetch();
        $nameuser = $result[0];
        $surnameuser = $result[1];
        $d = date("d"); // текущий день
        $dc = date("d", strtotime($c['datetime'])); // день из лога
        if ($d == $dc) { // сравниваем их, если равны, то писать только время
            $dc = date("H:i", strtotime($c['datetime']));
            $isDeletable = true;
        } else {
            $dc = date("d.m H:i", strtotime($c['datetime']));
            $isDeletable = false;
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id FROM uploads WHERE comment_id=:commentId');
        $filesQuery->execute(array(':commentId' => $c['id']));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        include 'engine/ajax/frontend/comment.php';
    }
} else {
    include 'engine/ajax/frontend/no-comments.php';
}
include 'engine/ajax/frontend/comments-script.php';
