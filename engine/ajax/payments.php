<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';
require_once __ROOT__ . '/engine/backend/other/TinkoffMerchantAPI.php';

$tariffPrices = [
    '1' => 299,
    '3' => 249 * 3,
    '12' => 199 * 12,
];

if($_POST['module'] == 'getPaymentLink' && !empty($_POST['tariff'])) {
    $subscribe = filter_var($_POST['subscribe'], FILTER_SANITIZE_NUMBER_INT);
    $tariffForBuy = filter_var($_POST['tariff'], FILTER_SANITIZE_NUMBER_INT);
    if (!key_exists($tariffForBuy, $tariffPrices)) {
        exit;
    }
    $api = new TinkoffMerchantAPI(
        TTKEY,  //Ваш Terminal_Key
        TSKEY   //Ваш Secret_Key
    );
    $amount = $tariffPrices[$tariffForBuy] * 100; // цена услуги в копейках
    $orderId = createOrder($id, $amount);

    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'Description' => 'Оплата подписки. Количество месяцев: '. $tariffForBuy,
    ];
    if ($subscribe) {
        $paymentArgs['Recurrent'] = 'Y';
        $paymentArgs['CustomerKey'] = $id;
    }
    try {
        $api->init($paymentArgs);
    } catch (Exception $e) {
        ob_start();
        echo "Счёт не сформирован\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        echo $response['PaymentURL'];
    } else {
        ob_start();
        echo "Ошибка при создании счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
}
if($_POST['module'] == 'chargeSubscribe') {
    $api = new TinkoffMerchantAPI(
        TTKEY,  //Ваш Terminal_Key
        TSKEY   //Ваш Secret_Key
    );
    $amount = 299 * 100; // цена услуги в копейках
    $orderId = createOrder($id, $amount);

    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'CustomerKey' => $id,
        'Description' => 'Продление подписки',
    ];
    try {
        $api->init($paymentArgs);
    } catch (Exception $e) {
        ob_start();
        echo "Счёт не сформирован\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        $rebillId = getLastRebillId($id);
        if (!$rebillId) {
            echo 'В базе нет такого rebill ID';
            exit;
        }
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
        }
        $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
        if ($response['Success']) {
            updateOrderOnSuccess($response);
            echo 'Рекуррентный платёж проведен успешно';
            exit;
        } else {
            ob_start();
            echo "Рекуррентный платёж не проведен\n";
            var_dump($response);
            $error = ob_get_clean();
            addToPaymentsErrorLog($error);
        }
    } else {
        ob_start();
        echo "Ошибка при создании счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
}

if($_POST['module'] == 'cancelPayment' && !empty($_POST['orderId'])) {
    $orderId = filter_var($_POST['orderId'], FILTER_SANITIZE_NUMBER_INT);
    if ($idc != 1) {
        exit;
    }

    $api = new TinkoffMerchantAPI(
        TTKEY,  //Ваш Terminal_Key
        TSKEY   //Ваш Secret_Key
    );
    $amount = $tariffPrices[$tariffForBuy] * 100; // цена услуги в копейках
    $paymentId = getPaymentId($orderId);
    if (!$paymentId) {
        echo 'Не найден банковский номер операции по данному order ID';
        exit;
    }

    $paymentArgs = [
        'PaymentId' => $paymentId,
    ];
    try {
        $api->cancel($paymentArgs);
    } catch (Exception $e) {
        ob_start();
        echo "Ошибка при отменне счета/платежа\n";
        var_dump($e->getTrace());
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
    $response = json_decode(htmlspecialchars_decode($api->__get('response')), true);
    if ($response['Success']) {
        updateOrderOnSuccess($response);
        echo 'Отмена проведена успешно' ;
    } else {
        ob_start();
        echo "Ошибка при отмене счета\n";
        var_dump($response);
        $error = ob_get_clean();
        addToPaymentsErrorLog($error);
    }
}
