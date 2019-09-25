<?php
global $idc;
global $pdo;
global $roleu;

if ($roleu != 'ceo') {
    header('location:/');
    ob_flush();
    die;
}

require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';

$personalPromocode = DBOnce('personal_promo', 'company', 'id = ' . $idc);
$personalPromocodeLink = 'https://lusy.io/?promo=' . $personalPromocode;
$invitedCompaniesQuery = $pdo->prepare("SELECT * FROM company WHERE reg_from = :companyId ORDER BY promo_status ASC, datareg DESC");
$invitedCompaniesQuery->execute([':companyId' => $idc]);
$invitedCompanies = $invitedCompaniesQuery->fetchAll(PDO::FETCH_ASSOC);

//тестовые данные!!!
$waitingForApprove = 0;
$approved = 0;

foreach ($invitedCompanies as $company) {
    if ($company['promo_status'] == 1) {
        $approved++;
    } else {
        $waitingForApprove++;
    }
}

if ($approved <= 10) {
    $progressBarValue = round($approved / 20 * 100);
} else {
    $progressBarValue = 50 + round(($approved - 10) / 40 * 100);
}

$companyTariff = getCompanyTariff($idc);
$infinitePremium = false;
if ($companyTariff['payday'] > strtotime('1.1.2030')) {
    $infinitePremium = true;
}
