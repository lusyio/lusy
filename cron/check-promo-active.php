<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');
$cron = true;

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';
$startDate = strtotime('midnight -14 days');

$taskCreateCountQuery = $pdo->prepare("SELECT c.id, COUNT(t.id) as tasks_count, c.reg_from FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE c.promo_status = 0 AND c.datareg <= :startDate AND reg_from IS NOT NULL GROUP BY t.idcompany");
$taskCreateCountQuery->execute([':startDate' => $startDate]);
$taskCreateCount = $taskCreateCountQuery->fetchAll(PDO::FETCH_ASSOC);

$lastActivityQuery = $pdo->prepare("SELECT c.id, MAX(activity) FROM users u LEFT JOIN company c ON u.idcompany = c.id WHERE u.id > 1 GROUP BY c.idcompany");
$lastActivityQuery->execute();
$lastActivity = $lastActivityQuery->fetchAll(PDO::FETCH_KEY_PAIR);

$lastVisitLimit = strtotime('midnight -5 days');
$updatePromoStatusQuery = $pdo->prepare("UPDATE company SET promo_status = 1 WHERE id = :companyId");
foreach ($taskCreateCount as $row) {
    if ($row['tasks_count'] < 7 || $lastActivity[$row['id']] < $lastVisitLimit) {
        continue;
    } else {
        $updatePromoStatusQuery->execute([':companyId' => $row['id']]);
        activateRefCheck($row['id']);
    }
}
