<?php

require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';
require_once __ROOT__ . '/engine/backend/other/TinkoffMerchantAPI.php';

$postData = file_get_contents('php://input');
addToPaymentsErrorLog( 'Нотификация от банка' . $postData);
$notification = json_decode($postData, true);

$isTokenValid = checkTokens($notification);
if ($isTokenValid) {
    updateOrderOnNotification($notification);
    updateCompanyTariff($notification);

}

$output = ob_get_clean();
addToPaymentsErrorLog($output);

echo 'OK';

exit;
