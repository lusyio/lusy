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
    $addUserQuery = $pdo->prepare('INSERT INTO users(email, password, idcompany, role, name, surname, register_date) VALUES (:email, :password, :companyId, :role, :name, :surname, :registerDate)');
    $queryData = [
        ':email' => mb_strtolower($email),
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':companyId' => $companyId,
        ':role' => $position,
        ':name' => $name,
        ':surname' => $surname,
        ':registerDate' => date("Y-m-d"),
    ];
    $addUserQuery->execute($queryData);
    return $pdo->lastInsertId();
}

function addCompany($companyName, $companyLanguage)
{
    global $pdo;
    $addCompanyQuery = $pdo->prepare('INSERT INTO company(idcompany, lang, tariff, datareg, activated) VALUES (:companyName, :language, :premium, :registerDate, :activated)');
    $queryData = [
        ':companyName' => $companyName,
        ':language' => $companyLanguage,
        ':premium' => 0,
        ':registerDate' => date("Y-m-d"),
        ':activated' => 0,
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