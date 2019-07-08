<h5 class="font-weight-bold mb-4">Тарифный план</h5>
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h4 class="font-weight-bold">Стартовый</h4>
                <p><span class="text-secondary">Периодичность оплаты<br>1 месяц</span> - 299 руб./мес.</p>
                <button class="btn btn-secondary">Выбрать тариф</button>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold">Уверенный</h3>
                <p><span class="text-secondary">Периодичность оплаты<br>3 месяца</span> - 249 руб./мес.</p>
                <button class="btn btn-secondary" id="regPrice">Выбрать тариф</button>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-body">
                <h3 class="font-weight-bold">Босс</h3>
                <p><span class="text-secondary">Периодичность оплаты<br>12 месяцев</span> - 199 руб./мес.</p>
                <button class="btn btn-secondary" id="bossPrice">Выбрать тариф</button>
            </div>
        </div>
    </div>
</div>
<p class="mt-3"><strong>Внимание!</strong> Списание средств происходит путем автоплатежа - автоматического списания
    суммы средств с периодичностью,
    соответствующей выбранному тарифу. Подписку можно отменить в любой момент.</p>
<p>Нажимая кнопку "Выбрать тариф", вы подтверждаете, что ознакомились с понятием "автоплатеж" и с <a
            href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf" class="btn-link"
            target="_blank">Офертой
        рекуррентных
        платежей</a>.</p>
<hr>
<h5 class="font-weight-bold mb-3 mt-3">Операции</h5>

<div class="card mb-1">
    <div class="card-body d-flex" style="justify-content: space-between">
        <div style="width: 80px"><i class="fas fa-check text-success paymentIcon"></i></div>
        <div class="w-100">Списание средств по тарифному плану Стартовый</div>
        <div class="text-success" style="width: 150px">+ 299 руб.</div>
    </div>
</div>

<div class="card mb-1">
    <div class="card-body d-flex" style="justify-content: space-between">
        <div style="width: 80px"><i class="fas fa-times text-danger paymentIcon"></i></div>
        <div class="w-100">Неудачная попытка списать средства</div>
        <div class="text-danger" style="width: 150px">- 299 руб.</div>
    </div>
</div>

<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Тарифный план "Уверенный"</h5>
            </div>
            <div class="modal-body text-left">
                <p>Вы собираетесь оформить платную подписку:</p>
                <table class="table w-100 border">
                    <tr>
                        <td>Тариф</td>
                        <td>Уверенный</td>
                    </tr>
                    <tr>
                        <td>Период списания средств</td>
                        <td>3 месяца</td>
                    </tr>
                    <tr>
                        <td>Стоимость в месяц</td>
                        <td>249 рублей</td>
                    </tr>
                    <tr>
                        <td>Итого платеж</td>
                        <td class="font-weight-bold">747 рублей</td>
                    </tr>
                </table>
                <p><input type="checkbox" id="offerta" style=" position: relative; top: 7px; margin-right: 10px; ">Я согласен с <a
                            href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                            class="btn-link" target="_blank">Офертой
                        рекуррентных
                        платежей</a>.</p>
                <hr>
                <button class="btn btn-secondary w-100" id="pay" disabled>Оплатить подписку</button>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#regPrice").on('click', function () {
            $('#payModal').modal('show');
        });
        $('#offerta').on('change', function () {
            if ($(this).is(':checked')){
                $('#pay').attr('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
            } else {

            }
        });
        $("#bossPrice").on('click', function () {
            $('#payModal').modal('show');
        })
    });
</script>