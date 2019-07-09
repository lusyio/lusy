<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';

$paidSubscribersQuery = $pdo->prepare("SELECT company_id, tariff, payday, is_card_binded, rebill_id, pan FROM company_tariff WHERE tariff > 0 AND payday < :checkTime");
$checkTime = strtotime('midnight next day');
$paidSubscribersQuery->execute([':checkTime' => $checkTime]);
$paidSubscribers = $paidSubscribersQuery->fetchAll(PDO::FETCH_ASSOC);

//foreach ($paidSubscribers as $company) {
//    if ($company['payday'] > time()) {
//        chargePayment($company['company_id']);
//    } elseif ($company['payday'] < strtotime('midnight +7 days')) {
//        chargeOverduePayment();
//    } else {
//        changeTariff($company['company_id'], 0);
//    }
//}


