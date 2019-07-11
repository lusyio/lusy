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

if($_POST['module'] == 'unbindCard') {
    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
        'errorText' => '',
    ];
    $result['status'] = unbindCard($idc);
    if ($result['status']) {
        addUnbindCardEvent($idc);
    }
    echo json_encode($result);
    exit;
}

if($_POST['module'] == 'changeTariff' && !empty($_POST['tariff'])) {
    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
        'errorText' => '',
    ];

    $selectedTariff = filter_var($_POST['tariff'], FILTER_SANITIZE_NUMBER_INT);

    // Выдаем ошибку при попытке смены текущего тарифа на этот же тариф
    $companyTariff = getCompanyTariff($idc);
    if ($selectedTariff == $companyTariff['tariff']) {
        $result['error'] = 'It is your current tariff';
        echo json_encode($result);
        exit;
    }
    // Если уже есть платный тариф и карта привязана то оплату не производим, просто меняем тариф
    if ($companyTariff['tariff'] != 0 && $companyTariff['is_card_binded']) {
        if (changeTariff($idc, $selectedTariff)) {
            $result['status'] = 'Tariff has been changed';
        } else {
            $result['error'] = 'Tariff has not been changed';
        }
        echo json_encode($result);
        exit;
    }
}

if($_POST['module'] == 'getPaymentLink' && !empty($_POST['tariff'])) {

    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
        'errorText' => '',
    ];

    $selectedTariff = filter_var($_POST['tariff'], FILTER_SANITIZE_NUMBER_INT);

    // Выдаем ошибку при попытке смены текущего тарифа на этот же тариф
    $companyTariff = getCompanyTariff($idc);
    if ($selectedTariff == $companyTariff['tariff']) {
        $result['error'] = 'It is your current tariff';
        echo json_encode($result);
        exit;
    }
    // Если уже есть платный тариф и карта привязана то оплату не производим, просто меняем тариф
    if ($companyTariff['tariff'] != 0 && $companyTariff['is_card_binded']) {
        if (changeTariff($idc, $selectedTariff)) {
            $result['status'] = 'Tariff has been changed';
        } else {
            $result['error'] = 'Tariff has not been changed';
        }
        echo json_encode($result);
        exit;
    }

    // Подключаем Класс Тинькофф АПИ
    $api = new TinkoffMerchantAPI(TTKEY,  TSKEY);

    $financeEvents = getFinanceEvents($idc);
    $wasUsedFreePeriod = false;
    foreach ($financeEvents as $event){
        if ($event['event'] == 'tariffChange' && $event['comment'] > 0){
            $wasUsedFreePeriod = true;
            break;
        }
    }
    $tariffInfo = getTariffInfo($selectedTariff);

    if (!$wasUsedFreePeriod) {
        //Создаем платеж в 1 рубль
        $orderId = createMinimumOrder($idc, $selectedTariff, $id);
        $amount = 100;
        $ForRefund = 1;
    } else {
        // Создаем внутренний заказ, выдаем ошибку если тариф не найден
        $orderId = createOrder($idc, $selectedTariff, $id);
        if (!$orderId) {
            $result['error'] = 'Tariff not found';
            echo json_encode($result);
            exit;
        }
        $amount = $tariffInfo['price'];
        $ForRefund = 0;
    }

    // Формируем массив данных для создания ссылки на оплату - стоимость в копейках, номер внутреннего заказа,
    // флаг рекуррентного платежа, ИД компании, Описание платежа, отображаемое на банковской странице оплаты
    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'Recurrent' => 'Y',
        'CustomerKey' => $idc,
        'SuccessURL' => 'https://s.lusy.io/payment/',
        'Description' => 'Оплата подписки по тарифу "' . $tariffInfo['tariff_name'] . '" (' . $tariffInfo['period_in_months'] . ' ' . ngettext('month', 'months', $tariffInfo['period_in_months']) . ')',
        'DATA' => [
            'ForRefund' => $ForRefund,
        ],
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
        echo json_encode($result);
        exit;
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
    echo json_encode($result);
}


if($_POST['module'] == 'chargeSubscribe') {
    $chargeResult = chargePayment($idc);
    echo json_encode($chargeResult);
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

if($_POST['module'] == 'refund' && !empty($_POST['orderId'])) {
    $orderId = filter_var($_POST['orderId'], FILTER_SANITIZE_NUMBER_INT);
    $order = getOrderInfo($orderId);
    if ($order['amount'] == 100) {
        $result['error'] = 'Cant refund initial recurrent payment';
        echo json_encode($result);
        exit;
    }
    $result = refundPayment($orderId);
    if ($result['error'] == '') {
        //setTomorrowAsPayday($idc);
        $unbindResult = unbindCard($idc);
        if ($unbindResult) {
            addUnbindCardEvent($idc);
        }
    }
    echo json_encode($result);
    exit;
}
