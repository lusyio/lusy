<?php
session_start();
ob_start();
include 'conf.php';

include 'engine/backend/other/header.php'; 
include 'engine/frontend/other/header.php';

// проверка на страницы логина и подобные
if (!empty($_GET['folder'])) {
    $folder = $_GET['folder'];
    if (!empty($folder)) {
        if (in_array($folder, $pages)) {
            inc('other', $folder);
        }
    }
}
// Проверка на страницу восстановления пароля
if (isset($_GET['restore']) && isset($_GET['code']))
{
    inc('other', 'restore-password');
}
// Проверка на страницу активации аккаунта
if (isset($_GET['activate']) && isset($_GET['code']))
{
    inc('other', 'activate');
}
if (isset($_GET['join']))
{
    inc('other', 'join');
}

function makeTimeStampInComments()
{
    global $pdo;
    $sql = 'SHOW COLUMNS FROM `comments`';
    $sql = $pdo->prepare($sql);
    $sql->execute();
    $columns = $sql->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        if ($column['Field'] != 'datetime' && $column['Type'] != 'datetime') {
            $getDateTimeQuery = $pdo->prepare('SELECT id, datetime FROM comments');
            $getDateTimeQuery->execute();
            $commentsDateTime = $getDateTimeQuery->fetchAll(PDO::FETCH_ASSOC);
            $deleteQuery = $pdo->prepare('alter table comments drop column datetime');
            $deleteQuery->execute();
            $createQuery = $pdo->prepare('alter table comments add datetime int');
            $createQuery->execute();
            $updateQuery = $pdo->prepare('update comments set datetime=:datetime WHERE id=:id');
            foreach ($commentsDateTime as $comment) {
                $updateQuery->execute(array(':id' => $comment['id'], ':datetime' => strtotime($comment['datetime'])));
            }
            break;
        }
    }
}

include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';