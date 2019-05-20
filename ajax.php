<?php
if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

    // подключаем pdo
    require_once(realpath('conf.php'));

    require_once 'engine/backend/functions/common-functions.php';
    require_once 'engine/backend/functions/login-functions.php';


    if ($_POST['ajax'] == 'restore-password' || $_POST['ajax'] == 'reg') {

        $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $russianSpeakerCodes = ['ru', 'be', 'uk'];
        if (in_array($userLanguage, $russianSpeakerCodes)) {
            $langc = 'ru';
        } else {
            $langc = 'en';
        }

    } else {
        // Получаем userId из Cookies
        $token = $_COOKIE['token'];
        $id = parseCookie($token)['uid'];

        // вычисляем id компании пользователя
        $idc = DBOnce('idcompany', 'users', 'id=' . $id);

        // определяем язык компании
        $langc = DBOnce('lang', 'company', 'id=' . $idc);

        // подключаем языковой файл
        require_once(realpath('engine/backend/lang/' . $langc . '.php'));
    }
    // кладем запрос в переменную
    $zapros = filter_var($_POST['ajax'], FILTER_SANITIZE_STRING);

    // подключаем файл php
    require_once(realpath('engine/ajax/' . $zapros . '.php'));
} else {
    header("location:/");
}