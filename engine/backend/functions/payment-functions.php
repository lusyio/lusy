<?php

function createOrder($customerId, $amount)
{
    global $pdo;
    $createOrderQuery = $pdo->prepare("INSERT INTO orders (amount, customer_key, create_date) VALUES (:amount, :customerKey, :createDate)");
    $createOrderQuery->execute([':amount' => $amount, ':customerKey' => $customerId, ':createDate' => time()]);
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
    //=========Сверка токенов=========
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

    if($calculatedToken != $receivedToken) {
        addToPaymentsErrorLog('Токены не совпадают. Токен банка: ' . $receivedToken . ' рассчитанный токен ' . $calculatedToken);
        exit;
    }
    //======Конец сверки токенов======

    $updateOrderQuery = $pdo->prepare("UPDATE orders SET error_code = :errorCode, status = :status, rebill_id = :rebillId where order_id = :orderId");
    $updateOrderData = [
        ':errorCode' => $notification['ErrorCode'],
        ':status' => $notification['Status'],
        ':rebillId' => 0, //$notification['RebillId'],
        ':orderId' => $notification['OrderId'],
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
    $ordersQuery = $pdo->prepare("SELECT order_id, amount, customer_key, create_date, payment_id, status, error_code, rebill_id FROM orders");
    $ordersQuery->execute();
    $orders = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);
    return $orders;
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
