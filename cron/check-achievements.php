<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/achievement-functions.php';

$allCompaniesQuery = $pdo->prepare("SELECT id, timezone FROM company");
$allCompaniesQuery->execute();
$allCompanies = $allCompaniesQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($allCompanies as $company) {
    date_default_timezone_set($company['timezone']);
    if (date('j') == 1 && date('G') == 0) {
        $firstDay = strtotime("first day of last month midnight");
        $lastDay = strtotime("last day of last month midnight");
        checkTaskDoneLeaderAchievementsInCompany($company['id'], $firstDay, $lastDay);
        checkTaskOverduePerMonthInCompany($company['id'], $firstDay, $lastDay);
    }
}
