<?php

function createOrder($customerId, $tariff, $userId = 0, $firstPay = false)
{
    global $pdo;
    $tariffInfo = getTariffInfo($tariff);
    if (!$tariffInfo) {
        return false;
    }
    $amount = $tariffInfo['price']; // цена услуги в копейках

    $createOrderQuery = $pdo->prepare("INSERT INTO orders (amount, customer_key, create_date, tariff, user_id, first_pay) VALUES (:amount, :customerKey, :createDate, :tariff, :userId, :firstPay)");
    $createOrderQuery->execute([':amount' => $amount, ':customerKey' => $customerId, ':createDate' => time(), ':tariff' => $tariff, ':userId' => $userId, ':firstPay' => (int) $firstPay]);
    $orderId = $pdo->lastInsertId();
    return $orderId;
}

function createMinimumOrder($customerId, $tariff, $userId, $firstPay = false)
{
    global $pdo;
    $amount = 100; // цена услуги в копейках

    $createOrderQuery = $pdo->prepare("INSERT INTO orders (amount, customer_key, create_date, tariff, user_id, first_pay) VALUES (:amount, :customerKey, :createDate, :tariff, :userId, :firstPay)");
    $createOrderQuery->execute([':amount' => $amount, ':customerKey' => $customerId, ':createDate' => time(), ':tariff' => $tariff, ':userId' => $userId, ':firstPay' => (int) $firstPay]);
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

function getOrdersListForCompany($companyId)
{
    global $pdo;
    $ordersQuery = $pdo->prepare("SELECT order_id, amount, customer_key, create_date, payment_id, status, error_code, rebill_id, processed, first_pay FROM orders WHERE customer_key = :companyId");
    $ordersQuery->execute([':companyId' => $companyId]);
    $ordersResult = $ordersQuery->fetchAll(PDO::FETCH_ASSOC);
    $ordersKeys = array_column($ordersResult, 'order_id');
    $orders = array_combine($ordersKeys, $ordersResult);
    return $orders;
}

function getOrderInfo($orderId)
{
    global $pdo;
    $orderInfoQuery = $pdo->prepare("SELECT order_id, amount, customer_key, create_date, payment_id, status, error_code, rebill_id, tariff, pan, processed, first_pay FROM orders WHERE order_id = :orderId");
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
    $tariffInfoResult = $tariffInfoQuery->fetchAll(PDO::FETCH_ASSOC);
    $tariffList = [];
    foreach ($tariffInfoResult as $tariff) {
        $tariffList[$tariff['tariff_id']] = $tariff;
    }
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
    $ceoId = getCeoId($orderInfo['customer_key']);

    if ($orderInfo['status'] == 'REJECTED' && !$orderInfo['processed'] && $companyTariff['tariff'] == $newTariff['tariff_id']) {
        addWithdrawalFailedEvent($orderInfo['customer_key'], $notification['OrderId'], $orderInfo['amount'], $newTariff['tariff_id']);
        $mailData = [$orderInfo['customer_key'], $orderInfo['pan']];
        addMailToQueue('sendSubscribeProlongationFailedEmailNotification', $mailData, $ceoId);
    }

    if ($orderInfo['status'] == 'CONFIRMED' && !$orderInfo['processed']) {

        if ($companyTariff['tariff'] != $newTariff['tariff_id']) {
            changeTariff($orderInfo['customer_key'], $newTariff['tariff_id']);
        }

        $updateCompanyTariffQuery = $pdo->prepare('UPDATE company_tariff SET payday = :newPayday, rebill_id = :rebillId, is_card_binded = 1, pan = :pan WHERE company_id = :companyId');

        if ($companyTariff['tariff'] == 0) {
            $lastDay = strtotime('midnight');

        } else {
            $lastDay = $companyTariff['payday'];
        }
        $tariffPeriod = $newTariff['period_in_months'];

        if ($orderInfo['first_pay']) {
            $newPayDay = strtotime('+14 days midnight');
        }elseif ($orderInfo['amount'] == 100) {
            $newPayDay = $companyTariff['payday'];
        } elseif (date('d', $companyTariff['payday']) > 28) {
            $newPayDay = strtotime('first day of next month +' . $tariffPeriod . ' month', $lastDay);
        } else {
            $newPayDay = strtotime('+' . $tariffPeriod . ' month', $lastDay);
        }

        $queryData = [
            ':companyId' => $orderInfo['customer_key'],
            ':newPayday' => $newPayDay,
            ':rebillId' => $orderInfo['rebill_id'],
            ':pan' => $orderInfo['pan'],
        ];
        $updateCompanyResult = $updateCompanyTariffQuery->execute($queryData);

        if ($updateCompanyResult) {
            markOrderAsProcessed($notification['OrderId']);
            addWithdrawalEvent($orderInfo['customer_key'], $notification['OrderId'], $orderInfo['amount'], $newTariff['tariff_id']);
            if ($orderInfo['amount'] == 100) {
                addBindCardEvent($orderInfo['customer_key'], $notification['OrderId'], $notification['OrderId'], $notification['Pan']);
            }
            if ($companyTariff['tariff'] == $newTariff['tariff_id']) {
                if ($orderInfo['amount'] > 100) {
                    addTariffProlongationEvent($orderInfo['customer_key'], $newTariff['tariff_id'], $notification['OrderId']);
                    $mailData = [$orderInfo['customer_key'], $newTariff['tariff_name'], date('d.m.Y', $newPayDay), date('d.m.Y', $newPayDay), false];
                    addMailToQueue('sendSubscribePremiumEmailNotification', $mailData, $ceoId);
                }
            } else {
                if ($orderInfo['first_pay']) {
                    $mailData = [$orderInfo['customer_key'], $newTariff['tariff_name'], date('d.m.Y',$newPayDay), date('d.m.Y',$newPayDay), true];
                    addMailToQueue('sendSubscribePremiumEmailNotification', $mailData, $ceoId);
                } else {
                    $mailData = [$orderInfo['customer_key'], $newTariff['tariff_name'], date('d.m.Y',$newPayDay), date('d.m.Y',$newPayDay), false];
                    addMailToQueue('sendSubscribePremiumEmailNotification', $mailData, $ceoId);
                }
                addTariffChangeEvent($orderInfo['customer_key'], $newTariff['tariff_id'], $notification['OrderId']);
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

function changeTariff($companyId, $newTariff)
{
    global $pdo;

    $updateCompanyTariffQuery = $pdo->prepare('UPDATE company_tariff SET tariff = :newTariff WHERE company_id = :companyId');
    $queryData = [
        ':companyId' => $companyId,
        ':newTariff' => $newTariff,
    ];
    $updateCompanyTariffResult = $updateCompanyTariffQuery->execute($queryData);

    if ($newTariff == 0) {
        setTariffInCompany($companyId, 0);
        addTariffChangeEvent($companyId, 0);
    } else {
        setTariffInCompany($companyId, 1);
        addTariffChangeEvent($companyId, $newTariff);
    }

    if ($updateCompanyTariffResult) {
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

function unbindCard($companyId)
{
    global $pdo;

    $unbindCardQuery = $pdo->prepare('UPDATE company_tariff SET is_card_binded = NULL, rebill_id = NULL, pan = NULL WHERE company_id = :companyId');
    $unbindCardResult = $unbindCardQuery->execute([':companyId' => $companyId]);
    return $unbindCardResult;
}

function setTariffInCompany($companyId, $tariff)
{
    global $pdo;
    $setPremiumInCompanyQuery = $pdo->prepare('UPDATE company SET tariff = :newTariff WHERE id = :companyId');
    $setPremiumInCompanyQuery->execute([':companyId' => $companyId,':newTariff' => $tariff]);
}


function addWithdrawalEvent($companyId, $orderId, $amount, $comment)
{
    global $pdo;

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, amount, comment) VALUES 
(:event, :datetime, :companyId, :orderId, :amount, :comment)");
    $queryData = [
        ':event' => 'withdrawal',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':amount' => $amount,
        ':comment' => $comment,
    ];
    $addEventQuery->execute($queryData);
}

function addTariffChangeEvent($companyId, $newTariff, $orderId = 0)
{
    global $pdo;
    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, comment) VALUES 
(:event, :datetime, :companyId, :orderId, :comment)");
    $queryData = [
        ':event' => 'tariffChange',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':comment' => $newTariff,
    ];
    $addEventQuery->execute($queryData);
}

function addTariffProlongationEvent($companyId, $newTariff, $orderId = 0)
{
    global $pdo;
    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, comment) VALUES 
(:event, :datetime, :companyId, :orderId, :comment)");
    $queryData = [
        ':event' => 'tariffProlongation',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':comment' => $newTariff,
    ];
    $addEventQuery->execute($queryData);
}

function getFinanceEvents($companyId)
{
    global $pdo;

    $financeEventsQuery = $pdo->prepare("SELECT fin_event_id, event, event_datetime, company_id, order_id, amount, comment FROM finance_events WHERE company_id = :companyId ORDER BY fin_event_id DESC");
    $financeEventsQuery->execute([':companyId' => $companyId]);
    $financeEvents = $financeEventsQuery->fetchAll(PDO::FETCH_ASSOC);
    return $financeEvents;
}

function addWithdrawalFailedEvent($companyId, $orderId, $amount, $comment)
{
    global $pdo;

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, amount, comment) VALUES 
(:event, :datetime, :companyId, :orderId, :amount, :comment)");
    $queryData = [
        ':event' => 'withdrawalFailed',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':amount' => $amount,
        ':comment' => $comment,
    ];
    $addEventQuery->execute($queryData);
}

function addUnbindCardEvent($companyId)
{
    global $pdo;

    $panQuery = $pdo->prepare("SELECT pan FROM orders WHERE customer_key = :companyId AND pan IS NOT NULL ORDER BY create_date DESC LIMIT 1");
    $panQuery->execute([':companyId' => $companyId]);
    $pan = $panQuery->fetch(PDO::FETCH_COLUMN);

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, comment) VALUES 
(:event, :datetime, :companyId, :pan)");
    $queryData = [
        ':event' => 'unbindCard',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':pan' => $pan,
    ];
    $addEventQuery->execute($queryData);
}

function setPayday($companyId, $newDate)
{
    global $pdo;
    $setPaydayQuery = $pdo->prepare('UPDATE company_tariff SET payday = :newDate WHERE company_id = :companyId');
    $setPaydayResult = $setPaydayQuery->execute([':newDate' => $newDate, ':companyId' => $companyId]);
    return $setPaydayResult;
}

function addRefundEvent($companyId, $orderId, $amount)
{
    global $pdo;

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, amount) VALUES 
(:event, :datetime, :companyId, :orderId, :amount)");
    $queryData = [
        ':event' => 'refund',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':amount' => $amount,
    ];
    $addEventQuery->execute($queryData);
}

function refundPayment($orderId)
{
    $result = [
        'error' => '',
        'status' => '',
        'errorText' => '',
    ];
    $order = getOrderInfo($orderId);
    if ($order['status'] != 'CONFIRMED') {
        $result['error'] = 'Payment was not confirmed';
        return $result;
    }
    if ($order['first_pay']) {
        if (time() - $order['create_date'] > 14 * 24 * 3600) {
            $result['error'] = 'Its too late';
            return $result;
        }
    } else {
        if (time() - $order['create_date'] > 24 * 3600) {
            $result['error'] = 'Its too late';
            return $result;
        }
    }
    $api = new TinkoffMerchantAPI(
        TTKEY,  //Ваш Terminal_Key
        TSKEY   //Ваш Secret_Key
    );
    $amount = $order['amount']; // цена услуги в копейках
    $paymentId = $order['payment_id'];
    if (!$paymentId) {
        $result['error'] = 'Payment ID not found for this order';
        return $result;
    }

    $paymentArgs = [
        'PaymentId' => $paymentId,
        'Amount' => $amount,
    ];
    try {
        $api->cancel($paymentArgs);
    } catch (Exception $e) {
        //При ошибке отмены платежа записываем стектрейс в лог и выдаем ошибку
        ob_start();
        echo "Ошибка при отмене счета/платежа\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = 'Refund error';
        return $result;
    }
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        addRefundEvent($order['customer_key'], $orderId, $order['amount']);
        $result['status'] = 'Successfully refunded';
    } else {
        ob_start();
        echo "Ошибка при отмене счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = $response['ErrorCode'];
        $result['errorText'] = $response['Details'];
    }
    return $result;
}

function getPromocodeInfo($promocodeName)
{
    global $pdo;
    $promocodeInfoQuery = $pdo->prepare("SELECT promocode_id, promocode_name, days_to_add, is_multiple, valid_until, used FROM promocodes WHERE promocode_name = :promocode");
    $promocodeInfoQuery->execute([':promocode' => $promocodeName]);
    $promocodeInfo = $promocodeInfoQuery->fetch(PDO::FETCH_ASSOC);
    return $promocodeInfo;
}

function activatePromocode($companyId, $promocodeName)
{
    global $pdo;
    $ceoId = getCeoId($companyId);
    $promocodeInfo = getPromocodeInfo($promocodeName);
    if (!$promocodeInfo) {
        return false;
    }
    $companyTariff = getCompanyTariff($companyId);
    if ($companyTariff['tariff'] == 0) {
        $newTariff = 1;
        changeTariff($companyId, $newTariff);
        $newTariffInfo = getTariffInfo($newTariff);
        $payday = strtotime('+' . $promocodeInfo['days_to_add'] . ' days midnight');
        $mailData = [$companyId, $newTariffInfo['tariff_name'], $promocodeInfo['days_to_add']];
        addMailToQueue('sendSubscribePromoEmailNotification', $mailData, $ceoId);
    } else {
        $payday = strtotime('+' . $promocodeInfo['days_to_add'] . ' days midnight', $companyTariff['payday']);

    }
    setPayday($companyId, $payday);
    addActivatePromocodeEvent($companyId, $promocodeName);
    if ($promocodeInfo['is_multiple'] != 1) {
        markPromocodeAsUsed($promocodeName);
    }
    return true;
}

function addActivatePromocodeEvent($companyId, $promocodeName)
{
    global $pdo;

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, comment) VALUES 
(:event, :datetime, :companyId, :comment)");
    $queryData = [
        ':event' => 'promocode',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':comment' => $promocodeName,
    ];
    $addEventQuery->execute($queryData);
}

function checkPromocodeForUsedByCompany($companyId, $promocodeName)
{
    global $pdo;
    $promocodeInfoQuery = $pdo->prepare("SELECT COUNT(*) FROM finance_events WHERE company_id = :companyId AND event = 'promocode' AND comment = :promocodeName");
    $promocodeInfoQuery->execute([':companyId' => $companyId, ':promocodeName' => $promocodeName]);
    $promocodeInfo = $promocodeInfoQuery->fetch(PDO::FETCH_COLUMN);
    return (boolean) $promocodeInfo;
}

function markPromocodeAsUsed($promocodeName)
{
    global $pdo;
    $markAsUsedQuery = $pdo->prepare("UPDATE promocodes SET used = 1 WHERE promocode_name = :promocodeName");
    $markAsUsedQuery->execute([':promocodeName' => $promocodeName]);
}

function addBindCardEvent($companyId, $orderId, $amount, $comment)
{
    global $pdo;

    $addEventQuery = $pdo->prepare("INSERT INTO finance_events (event, event_datetime, company_id, order_id, comment) VALUES 
(:event, :datetime, :companyId, :orderId, :comment)");
    $queryData = [
        ':event' => 'bindCard',
        ':datetime' => time(),
        ':companyId' => $companyId,
        ':orderId' => $orderId,
        ':comment' => $comment,
    ];
    $addEventQuery->execute($queryData);
}

function bindCard($companyId, $userId = 0, $tariff = null)
{
    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
        'errorText' => '',
    ];
    $companyTariff = getCompanyTariff($companyId);
    if (is_null($tariff)) {
        $selectedTariff = $companyTariff['tariff'];
    } else {
        $selectedTariff = $tariff;
    }
    $tariffInfo = getTariffInfo($companyTariff['tariff']);

    // Выдаем ошибку если карта уже привязана
    if ($companyTariff['is_card_binded']) {
        $result['error'] = 'Card is already bound';
        return $result;
    }

    // Выдаем ошибку, если привязываем карту к бесплатному тарифу
    if ($companyTariff['tariff'] == 0) {
        $result['error'] = 'Cant bind card to free tariff';
        return $result;
    }

    // Подключаем Класс Тинькофф АПИ
    $api = new TinkoffMerchantAPI(TTKEY,  TSKEY);

    $orderId = createMinimumOrder($companyId, $selectedTariff, $userId, false);
    if (!$orderId) {
        $result['error'] = 'Tariff not found';
        return $result;
    }
    $amount = 100;


    // Формируем массив данных для создания ссылки на оплату - стоимость в копейках, номер внутреннего заказа,
    // флаг рекуррентного платежа, ИД компании, Описание платежа, отображаемое на банковской странице оплаты
    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'Recurrent' => 'Y',
        'CustomerKey' => $companyId,
        'SuccessURL' => 'https://s.lusy.io/payment/',
        'Description' => 'Оплата подписки по тарифу "' . $tariffInfo['tariff_name'] . '" (' . $tariffInfo['period_in_months'] . ' ' . ngettext('month', 'months', $tariffInfo['period_in_months']) . ')',
    ];

    try {
        $api->init($paymentArgs);
    } catch (Exception $e) {
        //При ошибке создания счета записываем стектрейс в лог и выдаем ошибку
        ob_start();
        echo "Счёт не сформирован\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = 'Order not created';
        return $result;
    }
    // Получаем json ответ от АПИ банка, преобразуем его в массив
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);

    // При успешном статусе записываем ссылку на страницу оплаты, обновляем внутренний заказ полученными от
    // АПИ банка данными, при неудаче - делаем запись в лог и выдаем код ошибки АПИ банка
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        $result['url'] = $response['PaymentURL'];
    } else {
        ob_start();
        echo "Ошибка при создании счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
        $result['error'] = $response['ErrorCode'];
        $result['errorText'] = $response['Details'];
    }
    return $result;
}
