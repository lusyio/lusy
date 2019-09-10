<?php
global $idc;

$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
$taskCompany = DBOnce('idcompany', 'tasks', 'id = ' . $idtask);
if ($taskCompany != $idc) {
    exit;
}
if(isset($_POST['lastVisit'])) {
    $lastVisit = filter_var($_POST['lastVisit'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $lastVisit = 0;
}
$countcomments = DBOnce('COUNT(*) as count', 'comments', 'idtask=' . $idtask);


if ($countcomments > 0) {
    include __ROOT__ . '/engine/ajax/frontend/comments-header.php';
    $comments = $pdo->prepare('SELECT c.id, c.iduser, c.comment, c.status, c.datetime, c.view_status, t.manager 
FROM `comments` c LEFT JOIN tasks t on t.id = c.idtask WHERE idtask = :idtask ORDER BY datetime DESC, c.id DESC');
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
        $dc = date("d", $c['datetime']); // день из лога
        if ($d == $dc) { // сравниваем их, если равны, то писать только время
            $dc = date("H:i", $c['datetime']);
            $isDeletable = true;
        } else {
            $dc = date("d.m H:i", $c['datetime']);
            $isDeletable = false;
        }
        if (preg_match('~^request~', $c['status'])) {
            $commentStatus = 'request';
        } else {
            $commentStatus = $c['status'];
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
        $filesQuery->execute(array(':commentId' => $c['id'], ':commentType' => 'comment'));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        foreach ($files as $key => $file) {
            $fileNameParts = explode('.', $file['file_name']);
            $files[$key]['extension'] = mb_strtolower(array_pop($fileNameParts));
        }
        $commentViewStatus = json_decode($c['view_status'], true);
        if(is_null($commentViewStatus) || !isset($commentViewStatus[$id]['datetime'])) {
            $commentViewStatus[$id]['datetime'] = time();
            $commentViewStatusJson = json_encode($commentViewStatus);
            $viewQuery = $pdo->prepare('UPDATE `comments` SET view_status = :viewStatus where id=:commentId');
            $viewQuery->execute(array(':viewStatus' => $commentViewStatusJson, ':commentId' => $c['id']));
        }
        if ($lastVisit != 0 && $lastVisit < $c['datetime']) {
            $isNew = true;
        } else {
            $isNew = false;
        }
        if ($c['status'] == 'system') {
            include __ROOT__ . '/engine/ajax/frontend/comment-system.php';
        } else {
            include __ROOT__ . '/engine/ajax/frontend/comment.php';
        }
    }
} else {
    include __ROOT__ . '/engine/ajax/frontend/no-comments.php';
}
