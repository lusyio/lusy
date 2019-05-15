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

function addUser($login, $password, $email, $companyId, $position)
{
    global $pdo;
    $possiblePositions = ['ceo', 'admin', 'worker'];
    if (!in_array($position, $possiblePositions))
    {
        $position = 'worker';
    }
    $addUserQuery = $pdo->prepare('INSERT INTO users(login, email, password, idcompany, role) VALUES (:login, :email, :password, :companyId, :role)');
    $queryData = [
        ':login' => mb_strtolower($login),
        ':email' => mb_strtolower($email),
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':companyId' => $companyId,
        ':role' => $position,
    ];
    $addUserQuery->execute($queryData);
}

function addCompany($companyName, $companyLanguage)
{
    global $pdo;
    $addCompanyQuery = $pdo->prepare('INSERT INTO company(idcompany, lang, premium, datareg, activated) VALUES (:companyName, :language, :premium, :registerDate, :activated)');
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