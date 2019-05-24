<?php
global $pdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/settings-functions.php';

if ($_POST['module'] == 'changeAvatar') {
    if (isset($_FILES['avatar'])) {
        uploadAvatar();
    }
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
            $socialNetworks = filter_var($_POST['social'], FILTER_SANITIZE_STRING);
            $about = filter_var($_POST['about'], FILTER_SANITIZE_STRING);

            setNewUserData($name, $surname, $email, $phone, $socialNetworks, $about);

            if (isset($_POST['newPassword']) && trim($_POST['newPassword']) != '') {
                $newPassword = filter_var(trim($_POST['newPassword']), FILTER_SANITIZE_STRING);
                setNewPassword($newPassword);
            }
        }
    }
}

