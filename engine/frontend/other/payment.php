<?php
$payBefore = 0; // ранее не было оплат?
$cardBinded = 1; // карта привязана?
$countReports = 3; // доступно отчетов
$countTaskEdit = 3; // доступно раз расширенного функционала задач
?>
<div class="card mb-5 premiumCard">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5 text-center position-relative">
                <div>
                    <?php if ($companyTariff['tariff'] == 0): ?>
                        <?php if ($payBefore == 0): ?>
                            <p class="payZag">Попробуйте<br><span>Premium</span> доступ</p>
                        <?php else: ?>
                            <p class="payZag">Активируйте<br><span>Premium</span> доступ</p>
                        <?php endif; ?>
                        <button class="btn btn-light choose-tariff" data-price="299" data-price-per-month="299"
                                data-period="1 месяц" data-tariff-name="Стартовый" data-tariff-id="1">Подробнее
                        </button>
                    <?php endif; ?>
                    <?php if ($companyTariff['tariff'] == 1): ?>
                        <p class="payZag">У вас активирован<br><span>Premium</span> доступ</p>
                        <div class="dayLast">
                            Заканчивается<br>через 5 дней
                        </div>
                    <?php endif; ?>
                </div>
                <img class="diamond" src="/assets/svg/diamond.svg">
            </div>
            <div class="col-sm-7">
                <ul class="checkUl">
                    <li>Все, что есть в бесплатном тарифе</li>
                    <li>Расширенные настройки задач (отложенный старт, подзадачи, чек-листы, редактирование существующих
                        задач)
                    </li>
                    <li>Неограниченное количество задач</li>
                    <li>Детальные отчеты о деятельности компании</li>
                    <li>1 ГБ + интеграция с Google Drive и DropBox</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-sm-8">
        <div class="card">
            <div class="card-body">
                <h5>Текущий тариф - <?= $tariffInfo['tariff_name'] ?></h5>
                <?php if ($companyTariff['tariff'] == 0): ?>
                    <p class="ns">Безграничный период</p>
                    <ul class="plusUl">
                        <li>В хранилище файлов
                            свободно
                            <span><?= normalizeSize($remainingLimits['space'])['size'] ?> <?= normalizeSize($remainingLimits['space'])['suffix'] ?></span>
                            из 100 МБ
                        </li>
                        <li>Вы можете создать еще <span><?= $remainingLimits['tasks'] ?> задачи</span> из 150 возможных
                        </li>
                        <li>Вам доступно еще <span><?= $countReports ?> отчета</span> в этом месяце (макс. 3)</li>
                        <li>Вам доступно <span><?= $countTaskEdit ?> раза</span> возможность создать задачу с
                            расширенными настройками (макс. 3)
                        </li>
                    </ul>
                <?php else: ?>
                    <?php if ($cardBinded == 0): ?>
                        <p><span class="ns">Доступен до <?= date('d.m', $companyTariff['payday']); ?></span> <span
                                    class="small ns font-weight-light"> - далее вы будете переведены на бесплатный тариф</span>
                        </p>
                    <?php else: ?>
                        <p><span class="ns">Доступен до <?= date('d.m', $companyTariff['payday']); ?></span> <span
                                    class="small ns font-weight-light"> - далее будет произведено автоматическое списание средств, согласно ранее выбраному тарифу</span>
                        </p>
                    <?php endif; ?>
                    <ul class="plusUl">
                        <li>В хранилище файлов осталось места на
                            <span><?= normalizeSize($remainingLimits['space'])['size'] ?> <?= normalizeSize($remainingLimits['space'])['suffix'] ?></span>
                            из 1024 Мб
                        </li>
                        <li>Вам доступно неограниченное количество задач и возможность создавать их с
                            расширенными настройками
                        </li>
                        <li>Вам доступно неограниченное количество отчетов</li>
                        <li>Вам доступна интеграция с Google Drive и DropBox</li>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card position-relative text-center">
            <div class="card-body">
                <div class="gift-inside">
                    <h5 class="mb-5">Промокод</h5>
                    <input class="form-control" id="promoInput" placeholder="Введите промокод"
                           type="text">
                    <button class="btn btn-promocode" id="promoBtn">
                        Применить
                    </button>
                </div>
            </div>
            <img class="gift" src="/assets/svg/gift.svg">
        </div>
    </div>
</div>

<p class="text-grey"><strong>Внимание!</strong> Оплата тарифного плана происходит путем автоплатежа - автоматического
    списания суммы средств с периодичностью, соответствующей выбранному тарифу. Подписку можно отменить в любой момент.
</p>
<p class="text-grey">Нажимая кнопки "Сменить тариф", "Продлить подписку" или "Привязать карту для оплаты", вы
    подтверждаете, что
    ознакомились с понятием "автоплатеж" и с <a
            href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf" class="btn-link"
            target="_blank">Офертой рекуррентных платежей</a>.</p>

<h5 class="mb-3 mt-5">Операции</h5>
<?php foreach ($financeEvents as $event):
    include __ROOT__ . '/engine/frontend/other/payment-event.php';
endforeach; ?>
<?php if (count($financeEvents) == 0): ?>
    <div class="card mb-1 payment-card">
        <div class="card-body">
            <div class="row m-0">
                <div class="col text-muted text-center">
                    <span>
                        Вы еще не совершили операции по тарифам
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-tariff" role="document">
        <div class="d-flex">
            <div>
                <div class="modal-content border-0 left-modal pt-4 pb-4">
                    <div class="modal-header border-0 mb-3 text-center d-block">
                        <h5 class="modal-title text-left" style="margin-left: 22px;" id="exampleModalLabel">1. Выберите
                            подходящий тариф</h5>
                    </div>
                    <div class="modal-body text-left">
                        <div class="row mb-3">
                            <div class="col-2 pr-0 text-right">
                                <input id="rFirst" type="radio" name="radio" style="color: #28416b;font-weight: 600;">
                            </div>
                            <div class="col">
                                <div class="mb-2" style="color: #28416b;font-weight: 600;"><span>Стартовый</span></div>
                                <p class="text-muted-new">Переодичность оплаты - 1 месяц <br>
                                    Стоимость - 499 руб./мес
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-2 pr-0 text-right">
                                <input id="rSecond" type="radio" name="radio">
                            </div>
                            <div class="col">
                                <div class="mb-2" style="color: #28416b;font-weight: 600;"><span>Уверенный</span></div>
                                <p class="text-muted-new">Переодичность оплаты - 3 месяц <br>
                                    Стоимость - 349 руб./мес
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2 pr-0 text-right">
                                <input id="rThird" type="radio" name="radio">
                            </div>
                            <div class="col">
                                <div class="mb-2" style="color: #28416b;font-weight: 600;"><span>Босс</span></div>
                                <p class="text-muted-new">Переодичность оплаты - 12 месяц <br>
                                    Стоимость - 249 руб./мес
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="modal-content right-modal border-0 pt-4">
                    <div class="modal-header border-0 text-center d-block">
                        <h5 class="modal-title text-left" id="exampleModalLabel">2.
                            Подтвердите данные</h5>
                    </div>
                    <?php if ($companyTariff['tariff'] == 0): ?>
                        <div class="modal-body text-left">
                            <p class="text-muted-new">Вы собираетесь оформить платную подписку по тарифному плану "<span id="tariffName"></span>"
                            </p>
                            <table class="table w-100 border">
                                <tr>
                                    <td>Период списания средств</td>
                                    <td id="payPeriod"></td>
                                </tr>
                                <tr>
                                    <td>Стоимость в месяц</td>
                                    <td><span id="payPerMonth"></span> руб.</td>
                                </tr>
                                <tr>
                                    <td>Итого платеж</td>
                                    <td class="font-weight-bold"><span id="payFullPrice"></span> руб.</td>
                                </tr>
                            </table>
                            <?php if ($wasUsedFreePeriod): ?>
                                <p class="text-muted-new small">Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                            <?php else: ?>
                                <p class="text-muted-new small">Вы еще не использовали платный тариф - дарим вам 14 дней бесплатно. Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                            <?php endif; ?>
                            <p class="oferta-field"><input type="checkbox" id="oferta"
                                                           style=" position: relative; top: 7px; margin-right: 10px; ">
                                Я согласен с
                                <a href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                                         style="text-decoration: underline" target="_blank">
                                    Офертой рекуррентных платежей
                                </a>
                            </p>
                            <span class="position-absolute" id="disabledBtn">
                            adasd
                            </span>
                            <button class="btn text-white w-100 mt-3" id="pay" disabled style="height: 50px; background: #7b81f9;border-radius: 20px;">
                                Перейти к оплате подписки
                                <div class="spinner-border spinner-border-sm text-white" role="status"
                                     style="display: none">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <span class="icon-close-modal">
                    <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                                class="fas fa-times text-muted"></i></button>
                </span>
                    <?php else: ?>
                        <div class="modal-body text-left">
                            <p class="text-muted-new">Вы собираетесь сменить тариф</p>
                            <table class="table w-100 border">
                                <tr>
                                    <td>Период списания средств</td>
                                    <td id="payPeriod"></td>
                                </tr>
                                <tr>
                                    <td>Стоимость в месяц</td>
                                    <td><span id="payPerMonth"></span> руб.</td>
                                </tr>
                                <tr>
                                    <td>Дата следующего платежа</td>
                                    <td class="font-weight-bold"><?= date('d.m.Y', $companyTariff['payday']); ?></td>
                                </tr>
                                <tr>
                                    <td>Итого платеж</td>
                                    <td class="font-weight-bold"><span id="payFullPrice"></span> руб.</td>
                                </tr>
                            </table>
                            <?php if ($companyTariff['tariff'] == 0 || !$companyTariff['is_card_binded']): ?>
                                <p class="text-muted-new small">Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                            <?php endif; ?>
                            <p class="oferta-field"><input type="checkbox" id="oferta"
                                      style=" position: relative; top: 7px; margin-right: 10px; ">
                                Я согласен с
                                <a href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                                   style="text-decoration: underline" target="_blank">
                                    Офертой рекуррентных платежей
                                </a>
                            </p>
                            <span class="position-absolute" id="disabledBtn">
                            adasd
                            </span>
                            <hr>
                            <button class="btn text-white w-100" style="height: 50px; background: #7b81f9;border-radius: 20px;" id="changeTariff" disabled>
                                Сменить тариф
                                <div class="spinner-border spinner-border-sm text-white" role="status"
                                     style="display: none">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        </div>
                        <span class="icon-close-modal">
                    <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                                class="fas fa-times text-muted"></i></button>
                </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="modal-content border-0 modal-bottom">
            <div class="modal-body">
                <hr style="margin-left: 24px; margin-right: 24px;">
                <p class="text-muted-new small mb-0" style="margin-left: 24px;margin-right: 24px;">
                    <b>Внимание!</b> Оплата тарифного плана происходит путем автоплатежа - автоматического списания суммы
                    средств с периодичностью, соответствующей выбранному тарифу. Подписку можно отменить в любой момент.
                    Нажимая кнопки "Сменить тариф", "Продлить подписку" или "Привязать карту для оплаты", вы
                    подтверждаете, что ознакомились с понятием "автоплатеж" и с <a class="btn-link" href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf">Офертой рекуррентных платежей.</a>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 390px">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Управление картой</h5>
            </div>
            <div class="modal-body text-center">
                Вы действительно хотите отвязать карту?
            </div>
            <div class="modal-footer border-0" style="justify-content: space-between">
                <i id="deleteCardBtn" class="fas fa-check delete-card"></i>
                <i class="fas fa-times cancel-delete-card" data-dismiss="modal"></i>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addCardModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog d-flex" role="document">
        <div>
            <div class="modal-content right-modal border-0 pt-4">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title" id="exampleModalLabel">Тарифный план "<?= $tariffInfo['tariff_name']; ?>
                        "</h5>
                </div>
                <div class="modal-body text-left">
                    <p>Вы собираетесь привязать карту для оплаты подписки:</p>
                    <table class="table w-100 border">
                        <tr>
                            <td>Период списания средств</td>
                            <td><?= $tariffInfo['period_in_months']; ?> <?= ngettext('month', 'months', $tariffInfo['period_in_months']) ?></td>
                        </tr>
                        <tr>
                            <td>Стоимость в месяц</td>
                            <td><?= $tariffInfo['price'] / (100 * $tariffInfo['period_in_months']); ?> руб.</td>
                        </tr>
                        <tr>
                            <td>Дата следующего платежа</td>
                            <td class="font-weight-bold"><?= date('d.m.Y', $companyTariff['payday']); ?></td>
                        </tr>
                        <tr>
                            <td>Итого платеж</td>
                            <td class="font-weight-bold"><?= $tariffInfo['price'] / 100; ?> руб.</td>
                        </tr>
                    </table>
                    <p>Для привязывания карты мы спишем с вашей карты 1 рубль и вернём его</p>
                    <p class="oferta-field"><input type="checkbox" id="ofertaAddModal"
                                                   style=" position: relative; top: 7px; margin-right: 10px; ">Я
                        согласен с <a
                                href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                                class="btn-link" target="_blank">Офертой рекуррентных платежей</a>.</p>
                    <hr>
                    <span class="position-absolute" id="disabledBtn">
                            adasd
                        </span>
                    <button class="btn btn-secondary w-100" id="addCard" disabled style="height: 38px">
                        Привязать карту
                        <div class="spinner-border spinner-border-sm text-white" role="status"
                             style="display: none">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                </div>
                <span class="icon-close-modal">
                    <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                                class="fas fa-times text-muted"></i></button>
                </span>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="wrongPromoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 390px">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Промокод</h5>
            </div>
            <div class="modal-body text-center">
                Введен неверный промокод или уже использованный 😢
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content pb-3">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Вы уверены что хотите отменить операцию?</h5>
            </div>
            <div class="modal-body">
                <p class="small text-muted text-uppercase">Сумма к возврату</p>
                <span>
                   <i class="fas fa-coins text-muted paymentIcon"></i>
                    <span id="refundAmount"></span>
                </span>
                <hr>
                <p class="small text-muted text-uppercase">Дата и время создания платежа</p>
                <span>
                   <i class="far fa-clock text-muted paymentIcon"></i>
                    <span id="orderDate"></span>
                </span>
                <hr>
                <p class="">При отмене платежа мы прекращаем выполнять автоматические платежи с вашей карты и на
                    следующий день изменим ваш тариф на "Бесплатный"</p>
                <span class="text-muted small">Деньги обычно возвращаются на карту держателя в тот же день, но иногда (зависит от эмитента) могут идти до 3-х дней.</span>
            </div>
            <div class="modal-footer border-0" style="justify-content: center">
                <button class="btn bg-danger text-white w-100" id="refund">
                    Отменить операцию
                    <div class="spinner-border spinner-border-sm text-white" role="status"
                         style="display: none">
                        <span class="sr-only">Loading...</span>
                    </div>
                </button>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="refreshModal" tabindex="-1" role="dialog" aria-labelledby="refreshModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 390px">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="refreshModalLabel"></h5>
            </div>
            <div class="modal-body text-center">
                Для продолжения работы с Lusy.io необходимо обновить страницу
            </div>
            <div class="modal-footer border-0" style="justify-content: space-between">
                <button type="button" class="form-control btn btn-primary" data-dismiss="modal">Обновить страницу
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#disabledBtn').on('click', function () {
            $('.oferta-field').css({
                'background-color': 'rgba(255, 242, 242, 1)',
                'transition': '1000ms'
            });
            setTimeout(function () {
                $('.oferta-field').css('background-color', '#fff');
            }, 1000);
        });

        $('#promoBtn').on('click', function () {
            var promocode = $('#promoInput').val();
            if (promocode) {
                var fd = new FormData();
                fd.append('promocode', promocode);
                fd.append('module', 'usePromocode');
                fd.append('ajax', 'payments');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (response) {
                        if (response.error === '') {
                            $('#refreshModalLabel').text('Промокод активирован');
                            $('#refreshModal').modal('show');
                        } else {
                            $('#wrongPromoModal').modal('show');
                            console.log(response.error);
                        }
                    },
                })
            }
        });

        $('.delete-operation').on('click', function () {
            var orderId = $(this).data('order-id');
            var refundAmount = $(this).data('refund-amount');
            var orderDate = $(this).data('order-date');
            $('#refundAmount').text(refundAmount);
            $('#orderDate').text(orderDate);
            $('#paymentInfo').modal('show');
            $('#refund').on('click', function () {
                $(this).attr('disabled', true);
                var fd = new FormData();
                fd.append('orderId', orderId);
                fd.append('module', 'refund');
                fd.append('ajax', 'payments');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.onprogress = function (e) {
                            $('.spinner-border-sm').show();
                        };
                        return xhr;
                    },
                    success: function (response) {
                        if (response.error === '') {
                            $('#paymentInfo').modal('hide');
                            $('#refreshModalLabel').text('Платёж был успешно отменен');
                            $('#refreshModal').modal('show');
                        } else {
                            console.log(response.error);
                        }
                    },
                    complete: function () {
                        $('.spinner-border-sm').hide();
                    },
                });
            })
        });

        $('#paymentInfo').on('hide.bs.modal', function () {
            $(this).attr('disabled', false);
            $('#refundAmount').text('');
            $('#orderDate').text('');
        });


        $(".choose-tariff").on('click', function () {
            var currentTariff = $('#currentTariff').val();
            var isCardBinded = $('#isCardBinded').val();
            var period = $(this).data('period');
            var pricePerMonth = $(this).data('price-per-month');
            var fullPrice = $(this).data('price');
            var tariffName = $(this).data('tariff-name');
            var tariff = $(this).data('tariff-id');

            if (tariff == currentTariff) {
                $('#oferta').attr('disabled', true);
                $('#disabledBtn').hide();
                $('#pay').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary').text('Это ваш текущий тариф');
                $('#changeTariff').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                if (isCardBinded) {
                    $('#pay').text('Это ваш текущий тариф');
                    $('#changeTariff').text('Это ваш текущий тариф');
                    $('#oferta').attr('disabled', true);
                } else {
                    $('#pay').text('Привязать карту для оплаты');
                    $('#oferta').attr('disabled', false);
                    $('#changeTariff').text('Привязать карту для оплаты');
                }
            }
            $('#payPeriod').text(period);
            $('#payPerMonth').text(pricePerMonth);
            $('#descriptionPrice').text(pricePerMonth);
            $('#payFullPrice').text(fullPrice);
            $('#tariffName').text(tariffName);
            $('#payModal').modal('show');

            $('#pay').on('click', function () {
                var fd = new FormData();
                fd.append('tariff', tariff);
                fd.append('module', 'getPaymentLink');
                fd.append('ajax', 'payments');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    xhr: function () {
                        var xhr = new XMLHttpRequest();

                        xhr.upload.onprogress = function (e) {
                            $('.spinner-border-sm').show();
                        };
                        return xhr;
                    },
                    success: function (response) {
                        if (response.error === '') {
                            if (response.url !== '') {
                                window.open(response.url);
                                $('#payModal').modal('hide');
                                $('#refreshModalLabel').text('Вы были перенаправлены на сайт Банка для совершения платежа');
                                $('#refreshModal').modal('show');
                            } else {
                                console.log(response.status);
                            }
                        } else {
                            console.log(response.error);
                        }
                    },
                    complete: function () {
                        $('.spinner-border-sm').hide();
                    },
                });
            });

            $('#changeTariff').on('click', function () {
                var fd = new FormData();
                fd.append('tariff', tariff);
                fd.append('module', 'changeTariff');
                fd.append('ajax', 'payments');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    xhr: function () {
                        var xhr = new XMLHttpRequest();

                        xhr.upload.onprogress = function (e) {
                            $('.spinner-border-sm').show();
                        };
                        return xhr;
                    },
                    success: function (response) {
                        if (response.error === '') {
                            if (response.url !== '') {
                                window.open(response.url);
                                $('#payModal').modal('hide');
                                $('#refreshModalLabel').text('Вы были перенаправлены на сайт Банка для совершения платежа');
                                $('#refreshModal').modal('show');
                            } else {
                                location.reload();
                            }
                        } else {
                            console.log(response.error);
                        }
                    },
                    complete: function () {
                        $('.spinner-border-sm').hide();
                    },
                });
            });
        });

        $('#deleteCardBtn').on('click', function () {
            var fd = new FormData();
            fd.append('module', 'unbindCard');
            fd.append('ajax', 'payments');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    if (response.status) {
                        location.reload();
                    } else {
                        console.log('Error');
                    }
                },
            });
        });
        $('#addCard').on('click', function () {
            var fd = new FormData();
            fd.append('module', 'bindCard');
            fd.append('ajax', 'payments');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                xhr: function () {
                    var xhr = new XMLHttpRequest();

                    xhr.upload.onprogress = function (e) {
                        $('.spinner-border-sm').show();
                    };
                    return xhr;
                },
                success: function (response) {
                    if (response.error === '') {
                        if (response.url !== '') {
                            window.open(response.url);
                            $('#addCardModal').modal('hide');
                            $('#refreshModalLabel').text('Вы были перенаправлены на сайт Банка для совершения платежа');
                            $('#refreshModal').modal('show');
                        } else {
                            console.log(response.status);
                        }
                    } else {
                        console.log(response.error);
                    }
                },
                complete: function () {
                    $('.spinner-border-sm').hide();
                },
            });
        });

        $('#payModal').on('hide.bs.modal', function () {
            $('#oferta').prop("checked", false);
            $('#pay').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary').text('Оплатить подписку');
            $('#changeTariff').attr('disabled', true).addClass('btn-secondary').text('Сменить тариф');
            $('#oferta').attr('disabled', false);
        });
        $('#addCardModal').on('hide.bs.modal', function () {
            $('#ofertaAddModal').prop("checked", false);
            $('#addCard').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary').text('Оплатить подписку');
            $('#ofertaAddModal').attr('disabled', false);
        });

        $('#refreshModal').on('hide.bs.modal', function () {
            location.reload();
        });

        $('#oferta').on('change', function () {
            if ($(this).is(':checked')) {
                $('#pay').attr('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                $('#changeTariff').attr('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                $('#disabledBtn').hide();
            } else {
                $('#pay').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                $('#changeTariff').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                $('#disabledBtn').show();
            }
        });
        $('#ofertaAddModal').on('change', function () {
            if ($(this).is(':checked')) {
                $('#addCard').attr('disabled', false).removeClass('btn-secondary').addClass('btn-primary');
                $('#disabledBtn').hide();
            } else {
                $('#addCard').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary');
                $('#disabledBtn').show();
            }
        });

    });
</script>