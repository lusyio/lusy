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



