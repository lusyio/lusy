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

//connection to comet-server

$cometUser = '2553';
$cometPass = 'ywg03ajXvYGrtoCp6pvy7GEFf4hFGW5IeXQMBtF8i53S7ZgfXHVyWmIVwqPGel8r';
$cometDsn = 'mysql:host=app.comet-server.ru;dbname=CometQL_v1';
$cometPdo = new PDO($cometDsn, $cometUser, $cometPass);
$cometPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';