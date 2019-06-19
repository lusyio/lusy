<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

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
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $surname = filter_var(trim($_POST['surname']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = preg_replace("~[^0-9]~", '', $_POST['phone']);
    $about = filter_var($_POST['about'], FILTER_SANITIZE_STRING);

    $vk = filter_var($_POST['vk'], FILTER_SANITIZE_URL);
    $vk = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?vk.com\/)?(\/)?~', '', $vk);
    $facebook = filter_var($_POST['facebook'], FILTER_SANITIZE_URL);
    $facebook = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?facebook.com\/)?(\/)?~', '', $facebook);
    $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_URL);
    $instagram = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?instagram.com\/)?(\/)?~', '', $facebook);
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

    $userName = trim(DBOnce('name', 'users', 'id=' . $id));
    $userSurname = trim(DBOnce('surname', 'users', 'id=' . $id));

    setNewUserData($name, $surname, $email, $phone, json_encode($socialNetworks), $about);

    if ($userName != $name || $userSurname != $surname) {
        createAlterAvatar($id);
    }
}
if ($_POST['module'] == 'changePassword') {
    if (isset($_POST['password'])) {
        $password = trim(filter_var($_POST['password'], FILTER_SANITIZE_STRING));
        $hash = DBOnce('password', 'users', 'id=' . $id);
        if (password_verify($password, $hash)) {
            if (isset($_POST['newPassword']) && trim($_POST['newPassword']) != '') {
                $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
                setNewPassword($newPassword);
            }
        }
    }
}

if ($_POST['module'] == 'changeCompanyData' && $roleu == 'ceo') {
    $companyName = filter_var(trim($_POST['companyName']), FILTER_SANITIZE_STRING);
    $companyFullName = filter_var(trim($_POST['companyFullName']), FILTER_SANITIZE_STRING);
    $companySite = filter_var(trim($_POST['companySite']), FILTER_SANITIZE_STRING);
    $companyDescription = filter_var(trim($_POST['companyDescription']), FILTER_SANITIZE_STRING);
    $companyTimezone = filter_var(trim($_POST['companyTimezone']), FILTER_SANITIZE_STRING);
    setNewCompanyData($companyName, $companyFullName, $companySite, $companyDescription, $companyTimezone);
}

