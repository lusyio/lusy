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

$paidSubscribersQuery = $pdo->prepare("SELECT company_id, tariff, payday, is_card_binded, rebill_id, pan FROM company_tariff WHERE tariff > 0 AND payday <= :checkTime");
//$checkTime = strtotime('midnight');
$paidSubscribersQuery->execute([':checkTime' => time()]);
$paidSubscribers = $paidSubscribersQuery->fetchAll(PDO::FETCH_ASSOC);
$timeToFree = strtotime('midnight -7 days');
$overdueTime = strtotime('midnight -1 day');
foreach ($paidSubscribers as $company) {
    if ($company['payday'] <= $timeToFree) {
        changeTariff($company['company_id'], 0);
        // TODO отправить письмо о смене тарифа из-за неоплаты
    } elseif ($company['payday'] <= $overdueTime) {
        chargePayment($company['company_id']);
        // TODO отправить письмо о 7 днях на оплату
    } else {
        if ($company['is_card_binded']) {
            chargePayment($company['company_id']);
        } else {
            changeTariff($company['company_id'], 0);
        }
    }
}


