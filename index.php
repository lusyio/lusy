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

include 'engine/backend/other/footer.php';
include 'engine/frontend/other/footer.php';