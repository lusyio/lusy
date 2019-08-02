<?php
$amount = '';
$canRefunded = false;

if ($event['event'] == 'withdrawal') {
    $icon = 'far fa-credit-card text-success';
    $text = 'Списание средств по тарифному плану "' . $tariffList[$event['comment']]['tariff_name'] . '"';
    $amount = $event['amount'] / 100 . ' руб.';
    $isRefundableFirstPay = $orders[$event['order_id']]['first_pay'] && $event['event_datetime'] < 14 * 24 * 60 * 60 && $orders[$event['order_id']]['status'] == 'CONFIRMED';
    $isRefundablePay = ($event['amount'] > 100 && time() - $event['event_datetime'] < 24 * 60 * 60) && $orders[$event['order_id']]['status'] == 'CONFIRMED';
    $canRefunded = $isRefundableFirstPay || $isRefundablePay;
}
if ($event['event'] == 'refund') {
    $icon = 'far fa-credit-card text-success';
    $text = 'Возврат средств';
    $amount = $event['amount'] / 100 . ' руб.';
}

if ($event['event'] == 'tariffProlongation') {
    $icon = 'fas fa-check text-success';
    $text = 'Тарифный план "' . $tariffList[$event['comment']]['tariff_name'] . '" продлен';
}
if ($event['event'] == 'tariffChange') {
    $icon = 'fas fa-check text-success';
    $text = 'Тарифный план изменен на "' . $tariffList[$event['comment']]['tariff_name'] . '"';
}
if ($event['event'] == 'withdrawalFailed') {
    $icon = 'fas fa-times text-danger';
    $text = 'Неудачная попытка списания средств по тарифному плану "' . $tariffList[$event['comment']]['tariff_name'] . '"';
    $amount = $event['amount'] / 100 . ' руб.';
}
if ($event['event'] == 'unbindCard') {
    $icon = 'far fa-credit-card text-secondary';
    $text = 'Карта ' . $event['comment'] . ' успешно отвязана';
}
if ($event['event'] == 'bindCard') {
    $icon = 'far fa-credit-card text-success';
    $text = 'Карта ' . $event['comment'] . ' успешно привязана';
}
if ($event['event'] == 'promocode') {
    $promocodeInfo = getPromocodeInfo($event['comment']);
    $icon = 'fas fa-percentage text-success';
    $text = 'Промокод  на ' . $promocodeInfo['days_to_add'] . ' ' . ngettext('day', 'days', $promocodeInfo['days_to_add']) .  ' активирован';
}
?>
<div class="card mb-1 payment-card" data-fin-event-id="<?= $event['fin_event_id']; ?>">
    <div class="card-body payment-event-card d-flex">
        <div class="paymentIcon-div"><i class="<?= $icon; ?> paymentIcon"></i></div>
        <div class="w-100">
            <div class="row m-0">
                <?= $text; ?>
            </div>
            <div class="row m-0 text-secondary">
                <?= date('d.m.Y H:i', $event['event_datetime']) ?>
            </div>
        </div>
        <div class="text-success amount-div-payment"><?= $amount; ?></div>
        <?php if ($canRefunded): ?>
            <span class="position-absolute bg-danger delete-operation" data-refund-amount="<?= $amount; ?>" data-order-date="<?= date('d.m.Y H:i', $orders[$event['order_id']]['create_date']); ?>" data-order-id="<?= $event['order_id']; ?>" data-toggle="tooltip" data-placement="left" title="Отменить операцию">
                <i class="fas fa-times text-white"></i>
            </span>
        <?php endif; ?>
    </div>
</div>
