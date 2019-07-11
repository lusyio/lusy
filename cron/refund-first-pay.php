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
require_once __ROOT__ . '/engine/backend/other/TinkoffMerchantAPI.php';

$ordersToRefundQuery = $pdo->prepare("SELECT order_id FROM orders WHERE status = 'CONFIRMED' AND amount = 100 AND cron = 1");
$ordersToRefundQuery->execute();
$ordersToRefund = $ordersToRefundQuery->fetchAll(PDO::FETCH_COLUMN);
$removeCronFromOrderQuery = $pdo->prepare("UPDATE orders SET cron = NULL WHERE order_id = :orderId");
foreach ($ordersToRefund as $order) {
    $refundResult = refundPayment($order);
    if ($refundResult['error'] = '') {
        $removeCronFromOrderQuery->execute([':orderId' => $order]);
    }
}
