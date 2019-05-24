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

    $fileName = $id;
    $filePath = $dirName . '/' . $fileName;
    move_uploaded_file($avatar['tmp_name'], $filePath);
}

