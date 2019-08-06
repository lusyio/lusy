<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once __ROOT__ . '/engine/backend/functions/settings-functions.php';
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

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
    $birthdate = filter_var($_POST['birthdate'], FILTER_SANITIZE_STRING);
    if (!preg_match('~[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}~', $birthdate)){
        $birthdate = null;
    }

    $vk = filter_var($_POST['vk'], FILTER_SANITIZE_URL);
    $vk = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?vk.com\/)?(\/)?~', '', $vk);
    $facebook = filter_var($_POST['facebook'], FILTER_SANITIZE_URL);
    $facebook = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?facebook.com\/)?(\/)?~', '', $facebook);
    $instagram = filter_var($_POST['instagram'], FILTER_SANITIZE_URL);
    $instagram = preg_replace('~((http[s]?:\/\/)?(www.)?(m.)?instagram.com\/)?(\/)?~', '', $instagram);
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
    $userNameQuery = $pdo->prepare("SELECT name, surname FROM users WHERE id = :userId");
    $userNameQuery->execute([':userId' => $id]);
    $userNameInDb = $userNameQuery->fetch(PDO::FETCH_ASSOC);
    $userName = trim($userNameInDb['name']);
    $userSurname = trim($userNameInDb['surname']);

    setNewUserData($name, $surname, $email, $phone, json_encode($socialNetworks), $about, $birthdate);

    if ($userName != $name || $userSurname != $surname) {
        createAlterAvatar($id);
    }
}
if ($_POST['module'] == 'changePassword') {
    if (isset($_POST['password'])) {
        $password = trim($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $hash = DBOnce('password', 'users', 'id=' . $id);
        if (password_verify($password, $hash)) {
            if (isset($_POST['newPassword']) && trim($_POST['newPassword']) != '') {
                $newPassword = trim($_POST['newPassword']);
                $newPassword = filter_var($newPassword, FILTER_SANITIZE_STRING);
                $passwordRule = '~^[\w\~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/<>?]{6,64}$~';
                if (!preg_match($passwordRule, $newPassword)) {
                    exit('Incorrect new password');
                }
                setNewPassword($newPassword);
            }
        }
    }
}

if ($_POST['module'] == 'changeCompanyData' && $roleu == 'ceo') {
    $companyName = trim($_POST['companyName']);
    $companyName = filter_var($companyName, FILTER_SANITIZE_STRING);
    $companyFullName = trim($_POST['companyFullName']);
    $companyFullName = filter_var($companyFullName, FILTER_SANITIZE_STRING);
    $companySiteInput = trim($_POST['companySite']);
    $companySiteInput = filter_var($companySiteInput, FILTER_SANITIZE_STRING);
    $companySiteParsed = [];
    preg_match('~(http[s]?:\/\/)?(www.)?(.*)~', $companySiteInput, $companySiteParsed);
    $companySite = array_pop($companySiteParsed);
    $companyDescription = trim($_POST['companyDescription']);
    $companyDescription = filter_var($companyDescription, FILTER_SANITIZE_STRING);
    $companyTimezone = trim($_POST['companyTimezone']);
    $companyTimezone = filter_var($companyTimezone, FILTER_SANITIZE_STRING);
    setNewCompanyData($companyName, $companyFullName, $companySite, $companyDescription, $companyTimezone);
}

if ($_POST['module'] == 'updateNotifications') {
    if (DBOnce('COUNT(*)', 'user_notifications', 'user_id=' . $id) == 0) {
        $createNotificationRowQuery = $pdo->prepare('INSERT INTO user_notifications(user_id) VALUES (:userId)');
        $createNotificationRowQuery->execute(array(':userId' => $id));
    }
    $unsafeNotifications = json_decode($_POST['notifications'],true);
    $notifications = [];
    foreach ($unsafeNotifications as $k => $v) {
        $key = filter_var($k, FILTER_SANITIZE_STRING);
        $notifications[$key] = (int) $v;
    }
    if (!$notifications['sleepTime']) {
        $notifications['startSleep'] = -1;
    }
    $updateNotificationSettingsQuery = $pdo->prepare("UPDATE user_notifications SET task_create = :taskCreate, task_overdue = :taskOverdue, comment = :comment, task_review = :taskReview, task_postpone = :taskPostpone, message = :message, achievement = :achievement, silence_start = :startSleep, silence_end = :endSleep, payment = :payment WHERE user_id = :userId");
    $queryData = [
        ':userId' => $id,
        ':taskCreate' => $notifications['taskCreate'],
        ':taskOverdue' => $notifications['taskOverdue'],
        ':comment' => $notifications['comment'],
        ':taskReview' => $notifications['taskReview'],
        ':taskPostpone' => $notifications['taskPostpone'],
        ':message' => $notifications['message'],
        ':achievement' => $notifications['achievement'],
        ':startSleep' => $notifications['startSleep'],
        ':endSleep' => $notifications['endSleep'],
        ':payment' => $notifications['payment'],
    ];

    $updateNotificationSettingsQuery->execute($queryData);

}

if ($_POST['module'] == 'initChange' && $roleu == 'ceo') {
    $result = [
        'error' => ''
    ];
    if (isset($_POST['companyName'])) {
        $newCompanyName = trim($_POST['companyName']);
        $newCompanyName = filter_var($newCompanyName, FILTER_SANITIZE_STRING);
        if ($newCompanyName == '') {
            $result['error'] = 'company';
            exit();
        }
    } else {
        $result['error'] = 'company';
        echo json_encode($result);
        exit();
    }

    $oldCompanyName = DBOnce('idcompany', 'company', 'id = ' . $idc);
    if ($newCompanyName != $oldCompanyName) {
        $updateCompanyNameQuery = $pdo->prepare("UPDATE company SET idcompany = :newCompanyName WHERE id = :companyId");
        $updateCompanyNameQuery->execute([':companyId' => $idc]);
    }

    if (isset($_POST['email'])) {
        $newEmail = trim($_POST['email']);
        $newEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ($newEmail == '') {
            $result['error'] = 'email';
            echo json_encode($result);
            exit();
        }
    } else {
        $result['error'] = 'email';
        echo json_encode($result);
        exit();
    }

    $oldEmail = DBOnce('email', 'users', 'id = ' . $id);
    if ($newEmail != $oldEmail) {
        $updateEmailQuery = $pdo->prepare("UPDATE users SET email = :newEmail WHERE id = :userId");
        $updateEmailQuery->execute([':userId' => $id]);
    }
    if (isset($_POST['password']) && isset($_POST['newPassword'])) {
        $password = trim($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $hash = DBOnce('password', 'users', 'id=' . $id);
        if (password_verify($password, $hash)) {
            if (isset($_POST['newPassword'])) {
                $newPassword = trim($_POST['newPassword']);
                $newPassword = filter_var($newPassword, FILTER_SANITIZE_STRING);
                if ($newPassword != '' && $newPassword != $password)
                {
                    $passwordRule = '~^[\w\~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/<>?]{6,64}$~';
                    if (!preg_match($passwordRule, $newPassword)) {
                        $result['error'] = 'password';
                        echo json_encode($result);
                        exit();
                    }
                    setNewPassword($newPassword);
                }
            }
        }
    } else {
        $result['error'] = 'password';
        echo json_encode($result);
        exit();
    }
}

