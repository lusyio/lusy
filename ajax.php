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

            $userInfoQuery = $pdo->prepare("SELECT u.idcompany, u.role, c.lang, c.tariff FROM users u LEFT JOIN company c ON u.idcompany = c.id WHERE u.id = :userId");
            $userInfoQuery->execute([':userId' => $id]);
            $userInfo = $userInfoQuery->fetch(PDO::FETCH_ASSOC);
            // вычисляем id компании пользователя
            $idc = $userInfo['idcompany'];
            // определяем язык компании
            $langc = $userInfo['lang'];
            // определяем роль пользователя
            $roleu = $userInfo['role'];
            // определяем тариф
            $tariff = $userInfo['tariff'];

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
