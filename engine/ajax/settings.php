<?php
global $pdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/settings-functions.php';
require_once 'engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'changeAvatar') {
    if (isset($_FILES['avatar'])) {
        uploadAvatar();
    }
}

if ($_POST['module'] == 'deleteAvatar') {
    deleteAvatar($id);
}

if ($_POST['module'] == 'changeData') {
    if (isset($_POST['password'])) {
        $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
        $hash = DBOnce('password', 'users', 'id=' . $id);
        if (password_verify($password, $hash)) {
            $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $phone = preg_replace("~[^0-9]~", '', $_POST['phone']);
            $about = filter_var($_POST['about'], FILTER_SANITIZE_STRING);

            $vk = filter_var($_POST['vk'], FILTER_SANITIZE_STRING);
            $facebook = filter_var($_POST['facebook'], FILTER_SANITIZE_STRING);
            $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_STRING);
            $socialNetworks = [];
            if ($vk != '') {
                $socialNetworks['vk'] = $vk;
            }
            if ($facebook != '') {
                $socialNetworks['facebook'] = $facebook;
            }
            if ($instagram != '') {
                $socialNetworks['instagram'] = $instagram;
            }

            $userName = trim(DBOnce('name', 'users', 'id='.$id));
            $userSurname = trim(DBOnce('surname', 'users', 'id='.$id));
            if ($userName != $name || $userSurname != $surname) {
                createAlterAvatar($id);
            }

            setNewUserData($name, $surname, $email, $phone, json_encode($socialNetworks), $about);

            if (isset($_POST['newPassword']) && trim($_POST['newPassword']) != '') {
                $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
                setNewPassword($newPassword);
            }
        }
    }
}

