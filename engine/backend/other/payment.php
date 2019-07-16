<?php

global $id;
global $idc;
global $pdo;

require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';

$companyTariff = getCompanyTariff($idc);

$tariffInfo = getTariffInfo($companyTariff['tariff']);

$tariffList = getTariffList();

$remainingLimits = getRemainingLimits();

$financeEvents = getFinanceEvents($idc);

$orders = getOrdersListForCompany($idc);

$firstPayDateQuery = $pdo->prepare("SELECT fe.event_datetime, fe.fin_event_id FROM orders o LEFT JOIN finance_events fe ON fe.order_id = o.order_id WHERE fe.event = 'withdrawal' AND o.first_pay = 1 AND o.status ='CONFIRMED' AND fe.company_id = :companyId");
$firstPayDateQuery->execute([':companyId' => $idc]);
$firstPayDate = $firstPayDateQuery->fetch(PDO::FETCH_COLUMN);

$refundDeadline = null;
if ($firstPayDate && $firstPayDate > time() - 14 * 24 * 60 * 60) {
    $refundDeadline = $firstPayDate + 14 * 24 * 60 * 60;
}


$wasUsedFreePeriod = false;

foreach ($financeEvents as $event){
    if ($event['event'] == 'tariffChange' && $event['comment'] > 0){
        $wasUsedFreePeriod = true;
        break;
    }
}

