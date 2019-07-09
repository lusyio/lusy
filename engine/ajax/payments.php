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

    $result = [
        'url' => '',
        'error' => '',
        'status' => '',
    ];

    $subscribe = filter_var($_POST['subscribe'], FILTER_SANITIZE_NUMBER_INT);
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

    // Создаем внутренний заказ, выдаем ошибку если тариф не найден
    $orderId = createOrder($idc, $selectedTariff, $id);
    if (!$orderId) {
        $result['error'] = 'Tariff not found';
        echo json_encode($result);
        exit;
    }

    $tariffInfo = getTariffInfo($selectedTariff);
    $amount = $tariffInfo['price'];
    
    // Формируем массив данных для создания ссылки на оплату - стоимость в копейках, номер внутреннего заказа,
    // флаг рекуррентного платежа, ИД компании, Описание платежа, отображаемое на банковской странице оплаты
    $paymentArgs = [
        'Amount' => $amount,
        'OrderId' => $orderId,
        'Recurrent' => 'Y',
        'CustomerKey' => $idc,
        'Description' => 'Оплата подписки по тарифу ' . $tariffInfo['tariff_name'] . '. Количество месяцев: '. $tariffInfo['period_in_months'],
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
        $result['error'] = 'Account not created';
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
    }
    echo json_encode($result);
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
