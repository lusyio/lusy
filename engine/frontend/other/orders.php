<h4 class="text-center pb-3">Созданные платежи</h4>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Сумма (руб.)</th>
            <th>Customer Key</th>
            <th>Дата создания</th>
            <th>Payment ID</th>
            <th>Статус</th>
            <th>Код ошибки</th>
            <th>ID рек. платежа</th>
            <th>Управление</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
        <tr class="<?= ($order['status'] == 'CONFIRMED')? 'table-success' : ''; ?>">
            <td>
                <?= $order['order_id']; ?>
            </td>
            <td>
                <?= $order['amount'] / 100; ?>
            </td>
            <td>
                <?= $order['customer_key']; ?>
            </td>
            <td>
                <?= date('d.m.Y',$order['create_date']); ?>
            </td>
            <td>
                <?= $order['payment_id']; ?>
            </td>
            <td>
                <?= $order['status']; ?>
            </td>
            <td>
                <?= $order['error_code']; ?>
            </td>
            <td>
                <?= $order['rebill_id']; ?>
            </td>
            <td>
                <?php if ($order['status'] == 'CONFIRMED'): ?>
                <button class="btn btn-primary btn-sm cancel-payment" data-order-id="<?= $order['order_id']; ?>">Отменить оплату</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function () {
        $('.cancel-payment').on('click', function () {
            var orderId = $(this).data('order-id');
            var fd = new FormData();
            fd.append('orderId', orderId);
            fd.append('module', 'cancelPayment');
            fd.append('ajax', 'payments');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    alert('Платёж отменен')
                },
            });
        })
    })
</script>
