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