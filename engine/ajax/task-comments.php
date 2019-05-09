<?php
$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
$countcomments = DBOnce('COUNT(*) as count', 'comments', 'idtask=' . $idtask);

if ($countcomments > 0) {
    include 'engine/ajax/frontend/comments-header.php';
    $comments = $pdo->prepare('SELECT id, iduser, comment, status, datetime, view_status 
FROM `comments` where idtask = :idtask ORDER BY datetime desc');
    $comments->execute(array(':idtask' => $idtask));
    $comments = $comments->fetchAll(PDO::FETCH_BOTH);

    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $idtask));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_ASSOC);

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
        if (preg_match('~^request~', $c['status'])) {
            $commentStatus = 'request';
        } else {
            $commentStatus = $c['status'];
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
        $filesQuery->execute(array(':commentId' => $c['id'], ':commentType' => 'comment'));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        $commentViewStatus = json_decode($c['view_status'], true);
        if(is_null($commentViewStatus) || !isset($commentViewStatus[$id]['datetime'])) {
            $commentViewStatus[$id]['datetime'] = $datetime;
            $commentViewStatusJson = json_encode($commentViewStatus);
            $viewQuery = $pdo->prepare('UPDATE `comments` SET view_status = :viewStatus where id=:commentId');
            $viewQuery->execute(array(':viewStatus' => $commentViewStatusJson, ':commentId' => $c[$id]));
        }
        include 'engine/ajax/frontend/comment.php';
    }
} else {
    include 'engine/ajax/frontend/no-comments.php';
}
include 'engine/ajax/frontend/comments-script.php';
