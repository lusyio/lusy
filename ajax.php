<?php
if (isset($_POST['ajax']) && !empty($_POST['ajax'])) {

    // подключаем pdo
    require_once(realpath('conf.php'));

    require_once 'engine/backend/functions/common-functions.php';

    if ($_POST['ajax'] == 'restore-password') {
        if (isset($_POST['email'])) {
            $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            $russianSpeakerCodes = ['ru', 'be', 'uk'];
            if (in_array($userLanguage, $russianSpeakerCodes)) {
                $idc = 'ru';
            } else {
                $idc = 'en';
            }
        } else {
            echo 'empty';
        }

    } else {
        // вычисляем user id
        $id = filter_var($_POST['usp'], FILTER_SANITIZE_NUMBER_INT) - 345;

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