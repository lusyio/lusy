<?php

/**
 * Загружает аватарку пользователя в upload/avatar/
 */
function uploadAvatar()
{
    global $idc;
    global $id;

    $avatar = $_FILES['avatar'];
    $maxFileSize = 1 * 1024 * 1024;
    if ($avatar['size'] > $maxFileSize || $avatar['size'] == 0) {
        return;
    }

    $dirName = 'upload/avatar/' . $idc;
    if (!realpath($dirName)) {
        mkdir($dirName, 0777, true);
    }

    $fileName = $id . '.png';
    $filePath = $dirName . '/' . $fileName;
    move_uploaded_file($avatar['tmp_name'], $filePath);
}

function setNewUserData($name, $surname, $email, $phone, $socialNetworks, $about)
{
    global $id;
    global $pdo;
    $setNewDataQuery = $pdo->prepare('UPDATE users SET name = :name, surname = :surname, email = :email, phone = :phone, social_networks = :socialNetworks, about = :about WHERE id = :userId');
    $setNewDataQuery->execute(array(':name' => $name, ':surname' => $surname, ':email' => $email, ':phone' => $phone, ':userId' => $id, ':socialNetworks' => $socialNetworks, ':about' => $about));
}

function setNewPassword($newPassword)
{
    global $id;
    global $pdo;
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $setNewPasswordQuery = $pdo->prepare('UPDATE users SET password = :newPassword WHERE id = :userId');
    $setNewPasswordQuery->execute(array(':newPassword' => $hash,  ':userId' => $id));
}

function getCompanyData()
{
    global $pdo;
    global $idc;
    $companyDataQuery = $pdo->prepare('SELECT idcompany, full_company_name, site, description, timezone FROM company WHERE id = :companyId');
    $companyDataQuery->execute(array(':companyId' => $idc));
    $result = $companyDataQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}

