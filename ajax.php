<?php
define('__ROOT__', __DIR__);

if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {
    session_cache_limiter('none');
    session_start();
    ob_start();
    setlocale(LC_ALL, 'ru_RU');
    $locale = 'ru_RU';
    putenv("LC_MESSAGES=" . $locale);
    setlocale(5, $locale, $locale);
    bindtextdomain($locale, realpath(__ROOT__ . '/engine/backend/lang/'));
    bind_textdomain_codeset($locale, 'UTF-8');
    textdomain($locale);
    // подключаем pdo
    require_once(realpath('conf.php'));

    require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
    require_once __ROOT__ . '/engine/backend/functions/login-functions.php';


    if ($_POST['ajax'] == 'restore-password' || $_POST['ajax'] == 'reg') {

        $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $russianSpeakerCodes = ['ru', 'be', 'uk'];
        if (in_array($userLanguage, $russianSpeakerCodes)) {
            $langc = 'ru';
        } else {
            $langc = 'en';
        }

    } else {
        if (!empty($_COOKIE['token'])) {
            // Получаем userId из Cookies
            $token = $_COOKIE['token'];
            $id = parseCookie($token)['uid'];

            // вычисляем id компании пользователя
            $idc = DBOnce('idcompany', 'users', 'id=' . $id);

            // определяем язык компании
            $langc = DBOnce('lang', 'company', 'id=' . $idc);

            // определяем роль пользователя
            $roleu = DBOnce('role', 'users', 'id=' . $id);

            // определяем тариф
            $tariff = DBOnce('tariff', 'company', 'id=' . $idc);

            // подключаем языковой файл
            require_once(realpath(__ROOT__ . '/engine/backend/lang/' . $langc . '.php'));

            // обновляем время последнего посещения
            setLastVisit();
        } else {
            die();

        }
    }

    // кладем запрос в переменную
    $zapros = filter_var($_POST['ajax'], FILTER_SANITIZE_STRING);
    // подключаем файл php
    require_once(realpath(__ROOT__ . '/engine/ajax/' . $zapros . '.php'));
} else {
    header("location:/");
}