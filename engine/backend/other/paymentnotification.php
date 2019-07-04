<?php

require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';
require_once __ROOT__ . '/engine/backend/other/TinkoffMerchantAPI.php';

$postData = file_get_contents('php://input');
addToPaymentsErrorLog( 'Нотификация от банка' . $postData);
$notification = json_decode($postData, true);

updateOrderOnNotification($notification);

echo 'OK';

exit;
