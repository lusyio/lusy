<?php
require_once 'engine/backend/functions/login-functions.php';

function fireUser($userId)
{
    global $id;
    global $pdo;

    if ($userId != $id && isUserInCompany($userId)) {
        $fireUserQuery = $pdo->prepare('UPDATE users SET is_fired = 1 WHERE id = :userId');
        $fireUserQuery->execute(array(':userId' => $userId));
        removeAllSessionsForUser($userId);
    }
}

function restoreUser($userId)
{
    global $id;
    global $pdo;

    if ($userId != $id && isUserInCompany($userId)) {
        $fireUserQuery = $pdo->prepare('UPDATE users SET is_fired = 0 WHERE id = :userId');
        $fireUserQuery->execute(array(':userId' => $userId));
    }
}

function isUserInCompany($userId)
{
    global $idc;
    global $pdo;

    $userCheckQuery = $pdo->prepare('SELECT COUNT(*) FROM users WHERE id = :userId AND idcompany = :companyId');
    $userCheckQuery->execute(array(':userId' => $userId, 'companyId' => $idc));
    return (boolean) $userCheckQuery->fetch(PDO::FETCH_COLUMN);
}