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
function getTariffList()
{
    global $pdo;
    $tariffInfoQuery = $pdo->prepare("SELECT tariff_id, tariff_name, price, period_in_months FROM tariffs ORDER BY tariff_id");
    $tariffInfoQuery->execute();
    $tariffList = $tariffInfoQuery->fetchAll(PDO::FETCH_ASSOC);
    return $tariffList;
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

function chargePayment($companyId)
{
    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
    ];

    $companyTariff = getCompanyTariff($companyId);
    if ($companyTariff['tariff'] == 0) {
        // Бесплатный тариф - оплата не требуется
        $result['error'] = 'Can not charge for free tariff';
        return $result;
    }
    if (!$companyTariff['is_card_binded']) {
        //карта не привязана, делаем запись в лог
        $error = 'Не привязана карта, компания - ' . $companyId;
        addToPaymentsErrorLog($error);
        //TODO Отправить письмо об отсутствии карты
        //TODO Добавить событие о невозможности оплаты
        $result['error'] = 'Card not found';
        return $result;
    }

    require_once __ROOT__ . '/engine/backend/other/TinkoffMerchantAPI.php';

    $tariffInfo = getTariffInfo($companyTariff['tariff']);

    $api = new TinkoffMerchantAPI(TTKEY, TSKEY);

    $amount = $tariffInfo['price'];
    $orderId = createOrder($companyId, $companyTariff['tariff']);

    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'CustomerKey' => $companyId,
        'Description' => 'Продление подписки на тариф ' . $tariffInfo['tariff_name'] . '. Количество месяцев: '. $tariffInfo['period_in_months'],
    ];
    try {
        $api->init($paymentArgs);
    } catch (Exception $e) {
        //При ошибке создания счета записываем стектрейс в лог
        ob_start();
        echo "Счёт не сформирован (cron)\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = 'Order not created';
        return $result;
    }

    // Получаем json ответ от АПИ банка, преобразуем его в массив
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);

    // При успешном статусе обновляем внутренний заказ полученными от АПИ банка данными и выполняем
    // рекуррентный платёж, при неудаче - делаем запись в лог и выдаем код ошибки АПИ банка
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        $rebillId = $companyTariff['rebill_id'];

        $paymentArgs = [
            'PaymentId' => $response['PaymentId'],
            'RebillId' => $rebillId,
        ];
        try {
            $api->charge($paymentArgs);
        } catch (Exception $e) {
            ob_start();
            echo "Ошибка при проведении рекуррентного платежа\n";
            var_dump($e->getTrace());
            $error = ob_get_clean();
            addToPaymentsErrorLog($error);
            $result['error'] = 'Recurrent payment error';
            return $result;
        }
        $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
        if ($response['Success']) {
            updateOrderOnSuccess($response);
            exit;
        } else {
            ob_start();
            echo "Рекуррентный платёж не проведен\n";
            var_dump($response);
            $error = ob_get_clean();
            addToPaymentsErrorLog($error);
            $result['error'] = $response['ErrorCode'];
            return $result;
        }
    } else {
        ob_start();
        echo "Ошибка при создании счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = 'Order not created';
        return $result;
    }
}
