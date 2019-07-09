<?php

function createOrder($customerId, $tariff, $userId = 0)
{
    global $pdo;
    $tariffInfo = getTariffInfo($tariff);
    if (!$tariffInfo) {
        return false;
    }
    $amount = $tariffInfo['price']; // цена услуги в копейках

    $createOrderQuery = $pdo->prepare("INSERT INTO orders (amount, customer_key, create_date, tariff, user_id) VALUES (:amount, :customerKey, :createDate, :tariff, :userId)");
    $createOrderQuery->execute([':amount' => $amount, ':customerKey' => $customerId, ':createDate' => time(), ':tariff' => $tariff, ':userId' => $userId]);
    $orderId = $pdo->lastInsertId();
    return $orderId;
}

function updateOrderOnSuccess($response)
{
    global $pdo;
    $updateOrderQuery = $pdo->prepare("UPDATE orders SET error_code = :errorCode, status = :status, payment_id = :paymentId where order_id = :orderId");
    $updateOrderData = [
        ':errorCode' => $response['ErrorCode'],
        ':status' => $response['Status'],
        ':paymentId' => $response['PaymentId'],
        ':orderId' => $response['OrderId'],
    ];
    $updateOrderQuery->execute($updateOrderData);
}

function updateOrderOnNotification($notification)
{
    global $pdo;
    $updateOrderQuery = $pdo->prepare("UPDATE orders SET error_code = :errorCode, status = :status, rebill_id = :rebillId, pan = :pan where order_id = :orderId");
    if (isset($notification['RebillId'])) {
        $rebillId = $notification['RebillId'];
    } else {
        $rebillId = null;
    }
    $updateOrderData = [
        ':errorCode' => $notification['ErrorCode'],
        ':status' => $notification['Status'],
        ':rebillId' => $rebillId,
        ':orderId' => $notification['OrderId'],
        ':pan' => $notification['Pan'],
    ];
    $updateOrderQuery->execute($updateOrderData);
}

function addToPaymentsErrorLog($text)
{
    $file = __ROOT__ . '/payments.log';
    $current = file_get_contents($file);
    $current .= date('d.m.Y H:i:s');
    $current .= "\n";
    $current .= $text;
    $current .= "\n";
    file_put_contents($file, $current);
}

function getOrdersList()
{
    global $pdo;
    $ordersQuery = $pdo->prepare("SELECT order_id, amount, customer_key, create_date, payment_id, status, error_code, rebill_id, processed FROM orders");
    $ordersQuery->execute();
    $orders = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);
    return $orders;
}

function getOrderInfo($orderId)
{
    global $pdo;
    $orderInfoQuery = $pdo->prepare("SELECT order_id, amount, customer_key, create_date, payment_id, status, error_code, rebill_id, tariff, pan, processed FROM orders WHERE order_id = :orderId");
    $orderInfoQuery->execute([':orderId' => $orderId]);
    $orderInfo = $orderInfoQuery->fetch(PDO::FETCH_ASSOC);
    return $orderInfo;
}

function getPaymentId($orderId)
{
    global $pdo;
    $paymentIdQuery = $pdo->prepare("SELECT payment_id FROM orders WHERE order_id = :orderId");
    $paymentIdQuery->execute([':orderId' => $orderId]);
    $paymentId = $paymentIdQuery->fetch(PDO::FETCH_COLUMN);
    if ($paymentId) {
        return $paymentId;
    } else {
        return false;
    }
}

function getLastRebillId($userId)
{
    global $pdo;
    $lastRebillIdQuery = $pdo->prepare("SELECT rebill_id FROM orders WHERE customer_key = :userId AND status = 'CONFIRMED' AND rebill_id IS NOT NULL ORDER BY create_date DESC LIMIT 1");
    $lastRebillIdQuery->execute([':userId' => $userId]);
    $lastRebillId = $lastRebillIdQuery->fetch(PDO::FETCH_COLUMN);
    if ($lastRebillId) {
        return $lastRebillId;
    } else {
        return false;
    }
}

function getTariffInfo($tariffId)
{
    global $pdo;
    $tariffInfoQuery = $pdo->prepare("SELECT tariff_id, tariff_name, price, period_in_months FROM tariffs WHERE tariff_id = :tariffId");
    $tariffInfoQuery->execute([':tariffId' => $tariffId]);
    $tariffInfo = $tariffInfoQuery->fetch(PDO::FETCH_ASSOC);
    return $tariffInfo;
}

function getCompanyTariff($companyId)
{
    global $pdo;
    $companyTariffQuery = $pdo->prepare("SELECT company_id, tariff, payday, is_card_binded, rebill_id, pan FROM company_tariff WHERE company_id = :companyId");
    $companyTariffQuery->execute([':companyId' => $companyId]);
    $companyTariff = $companyTariffQuery->fetch(PDO::FETCH_ASSOC);
    return $companyTariff;
}

function checkTokens($notification)
{
    $receivedToken = $notification['Token'];
    unset($notification['Token']);
    $token = '';
    $notification['Password'] = TSKEY;
    ksort($notification);

    foreach ($notification as $arg) {
        if (!is_array($arg)) {
            if (is_bool($arg)) {
                $token .= ($arg) ? 'true' : 'false';
            }else {
                $token .= $arg;
            }
        }
    }
    $calculatedToken = hash('sha256', $token);

    if($calculatedToken == $receivedToken) {
        return true;
    } else {
        addToPaymentsErrorLog('Токены не совпадают. Токен банка: ' . $receivedToken . ' рассчитанный токен ' . $calculatedToken);
        return false;
    }
}

function updateCompanyTariff($notification)
{
    global $pdo;
    $orderInfo = getOrderInfo($notification['OrderId']);
    $companyTariff = getCompanyTariff($orderInfo['customer_key']);
    $newTariff = getTariffInfo($orderInfo['tariff']);


    if ($orderInfo['status'] == 'CONFIRMED' && !$orderInfo['processed']) {
        $updateCompanyTariffQuery = $pdo->prepare('UPDATE company_tariff SET tariff = :newTariff, payday = :newPayday, rebill_id = :rebillId, is_card_binded = 1, pan = :pan WHERE company_id = :companyId');

        if ($companyTariff['tariff'] == 0) {
            $lastDay = strtotime('midnight');
        } else {
            $lastDay = $companyTariff['payday'];
        }
        $tariffPeriod = $newTariff['period_in_months'];
        if (date('d', $companyTariff['payday']) > 28) {
            $newPayDay = strtotime('first day of next month +' . $tariffPeriod . ' month', $lastDay);
        } else {
            $newPayDay = strtotime('+' . $tariffPeriod . ' month', $lastDay);
        }

        $queryData = [
            ':companyId' => $orderInfo['customer_key'],
            ':newTariff' => $orderInfo['tariff'],
            ':newPayday' => $newPayDay,
            ':rebillId' => $orderInfo['rebill_id'],
            ':pan' => $orderInfo['pan'],
        ];
        $updateCompanyResult = $updateCompanyTariffQuery->execute($queryData);

        if ($updateCompanyResult) {
            markOrderAsProcessed($notification['OrderId']);
            if ($companyTariff['tariff'] == $newTariff['tariff_id']) {
                addFinanceEvent($orderInfo['customer_key'], 'prolongation');
            }
        }
    }
}

function markOrderAsProcessed($orderId)
{
    global $pdo;
    $updateOrderQuery = $pdo->prepare("UPDATE orders SET processed = 1 where order_id = :orderId");
    $updateOrderQuery->execute([':orderId' => $orderId]);
}

function addFinanceEvent($companyId, $event)
{

}

function changeTariff($companyId, $newTariff)
{
    global $pdo;

    $updateCompanyTariffQuery = $pdo->prepare('UPDATE company_tariff SET tariff = :newTariff WHERE company_id = :companyId');
    $queryData = [
        ':companyId' => $companyId,
        ':newTariff' => $newTariff,
    ];
    $updateCompanyTariffResult = $updateCompanyTariffQuery->execute($queryData);
    if ($updateCompanyTariffResult) {
        addFinanceEvent($companyId, 'tariffChange');
    }
    return $updateCompanyTariffResult;
}
