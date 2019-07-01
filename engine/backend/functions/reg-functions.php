<?php

function getUserLanguage() {
    $userLanguage = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $russianSpeakerCodes = ['ru', 'be', 'uk'];
    if (in_array($userLanguage, $russianSpeakerCodes)) {
        $companyLanguage = 'ru';
    } else {
        $companyLanguage = 'en';
    }
    return $companyLanguage;
}

/**
 * @param $email e-mail as a login
 * @param $password
 * @param $companyId
 * @param $position 'ceo', 'admin' or 'worker'
 * @param string $name (optional)
 * @param string $surname (optional)
 * @return string ID of created user
 */
function addUser($email, $password, $companyId, $position, $name = '', $surname = '')
{
    global $pdo;
    $possiblePositions = ['ceo', 'admin', 'worker'];
    if (!in_array($position, $possiblePositions))
    {
        $position = 'worker';
    }
    $addUserQuery = $pdo->prepare('INSERT INTO users(email, password, idcompany, role, name, surname, register_date, last_viewed_chat_message) VALUES (:email, :password, :companyId, :role, :name, :surname, :registerDate, :registerDate)');
    $queryData = [
        ':email' => mb_strtolower($email),
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':companyId' => $companyId,
        ':role' => $position,
        ':name' => $name,
        ':surname' => $surname,
        ':registerDate' => time(),
    ];
    $addUserQuery->execute($queryData);
    $userId = $pdo->lastInsertId();

    $createNotificationRowQuery = $pdo->prepare('INSERT INTO user_notifications(user_id) VALUES (:userId)');
    $createNotificationRowQuery->execute(array(':userId' => $userId));

    return $userId;
}

function addCompany($companyName, $companyLanguage, $companyTimeZone)
{
    global $pdo;
    $addCompanyQuery = $pdo->prepare('INSERT INTO company(idcompany, lang, tariff, datareg, activated, timezone) VALUES (:companyName, :language, :premium, :registerDate, :activated, :companyTimeZone)');
    $queryData = [
        ':companyName' => $companyName,
        ':language' => $companyLanguage,
        ':premium' => 0,
        ':registerDate' => time(),
        ':activated' => 0,
        ':companyTimeZone' => $companyTimeZone,
    ];
    $addCompanyQuery->execute($queryData);
    return $pdo->lastInsertId();
}

function isLoginExist($login)
{
    global $pdo;
    $loginQuery = $pdo->prepare('SELECT id FROM users WHERE login=:login');
    $loginQuery->execute(array(':login' => mb_strtolower($login)));
    return (boolean) $loginQuery->fetch(PDO::FETCH_ASSOC);
}

function isEmailExist($email)
{
    global $pdo;
    $emailQuery = $pdo->prepare('SELECT id FROM users WHERE email=:email');
    $emailQuery->execute(array(':email' => mb_strtolower($email)));
    return (boolean) $emailQuery->fetch(PDO::FETCH_ASSOC);
}

function createActivationCode($companyId)
{
    global $pdo;
    $activationCode = str_shuffle(md5(time()));
    $addCode = $pdo->prepare('INSERT INTO company_activation(company_id, code) VALUES (:companyId,:activationCode)');
    $addCode->execute(array(':companyId' => $companyId, ':activationCode' => $activationCode));
    return $activationCode;
}
function getActivationCode($companyId)
{
    global $pdo;
    $codeQuery = $pdo->prepare('SELECT code FROM company_activation WHERE company_id = :companyId');
    $codeQuery->execute(array(':companyId' => $companyId));
    $code = $codeQuery->fetch(PDO::FETCH_COLUMN);
    return $code;
}
