<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="row">
                <div class="col-lg-5 col-12 card-tariff-left">
                    <div class="card-body ">
                        <span class="small text-muted">У вас активирован</span>
                        <h3>Пробный период</h3>
                        <p>
                            <span class="text-muted small">Плата автоматически спишется по истечению срока его действия</span>
                        </p>
                        <div class="d-flex">
                            <input class="form-control text-muted" id="promoInput" placeholder="Введите промокод"
                                   type="text">
                            <button class="btn btn-primary" id="promoBtn">
                                Применить
                            </button>
                        </div>
                        <span class="small text-muted">Если вы где-то нашли промокод, скорее вводите и получайте бонусы 🌟</span>
                    </div>
                </div>
                <div class="col-lg-7 col-12 card-tariff-right">
                    <div class="card-body">
                        <?php if ($companyTariff['is_card_binded']): // Если привязана карта?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="deleteCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#deleteCardModal">
                                    Отвязать карту
                                </button>
                            </div>
                        <?php else: // Если не привязана карта ?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="addCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#addCardModal">
                                    Привязать карту
                                </button>
                            </div>
                            <div style="z-index: 2">
                            <span class="text-muted fa-stack fa-1x">
                                <i class="far fa-credit-card fa-stack-1x icon-credit-card"></i>
                                <i class="fas fa-slash fa-stack-2x "></i>
                            </span>
                                <span><?= date('d.m', strtotime('+1 day', $companyTariff['payday'])); ?> ваш тарифный план изменится на Бесплатный</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="d-block text-muted bg-icon-ruble">
                <i class="fas fa-ruble-sign icon-ruble"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="row">
                <div class="col-12 col-lg-5 card-tariff-left">
                    <div class="card-body">
                        <span class="small text-muted">Ваш тарифный план</span>
                        <h2>Стартовый</h2>
                        <p>
                            <span class="small text-muted">На время действия промокода Lusy2019 до 12.08</span>
                        </p>
                        <div class="d-flex">
                            <input class="form-control text-muted" id="promoInput" placeholder="Введите промокод"
                                   type="text">
                            <button class="btn btn-primary" id="promoBtn">
                                Применить
                            </button>
                        </div>
                            <span class="small text-muted">Если вы где-то нашли промокод, скорее вводите и получайте бонусы 🌟</span>
                    </div>
                </div>
                <div class="col-lg-7 col-12 card-tariff-right">
                    <div class="card-body">
                        <?php if ($companyTariff['is_card_binded']): // Если привязана карта?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="deleteCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#deleteCardModal">
                                    Отвязать карту
                                </button>
                            </div>
                            <div style="z-index: 2">
                            <span class="text-muted">
                            <i class="far fa-credit-card icon-credit-card"></i>
                            </span>
                                <span><?= date('d.m', $companyTariff['payday']); ?> будет списание с карты <?= $companyTariff['pan']; ?> в размере <?= $tariffInfo['price'] / 100; ?> руб.</span>
                            </div>
                        <?php else: // Если не привязана карта ?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="addCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#addCardModal">
                                    Привязать карту
                                </button>
                            </div>
                            <div style="z-index: 2">
                            <span class="text-muted fa-stack fa-1x">
                                <i class="far fa-credit-card fa-stack-1x icon-credit-card"></i>
                                <i class="fas fa-slash fa-stack-2x "></i>
                            </span>
                                <span><?= date('d.m', strtotime('+1 day', $companyTariff['payday'])); ?> ваш тарифный план изменится на Бесплатный</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="d-block text-muted bg-icon-ruble">
                <i class="fas fa-ruble-sign icon-ruble"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col">
        <div class="card">
            <div class="row">
                <div class="col-12 col-lg-5 card-tariff-left">
                    <div class="card-body">
                        <span class="small text-muted">Ваш тарифный план</span>
                        <h2>Уверенный</h2>
                        <p>
                            <span class="small text-muted">Оплачен до 08.07</span>
                        </p>
                        <div class="d-flex">
                            <input class="form-control text-muted" id="promoInput" placeholder="Введите промокод"
                                   type="text">
                            <button class="btn btn-primary" id="promoBtn">
                                Применить
                            </button>
                        </div>
                        <span class="small text-muted">Если вы где-то нашли промокод, скорее вводите и получайте бонусы 🌟</span>
                    </div>
                </div>
                <div class="col-lg-7 col-12 card-tariff-right">
                    <div class="card-body">
                        <?php if ($companyTariff['is_card_binded']): // Если привязана карта?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="deleteCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#deleteCardModal">
                                    Отвязать карту
                                </button>
                            </div>
                            <div style="z-index: 2">
                            <span class="text-muted">
                            <i class="far fa-credit-card icon-credit-card"></i>
                            </span>
                                <span><?= date('d.m', $companyTariff['payday']); ?> будет списание с карты <?= $companyTariff['pan']; ?> в размере <?= $tariffInfo['price'] / 100; ?> руб.</span>
                            </div>
                        <?php else: // Если не привязана карта ?>
                            <div class="d-flex" style="justify-content: space-between">
                                <span class="small text-muted mt-1">Ваша банковская карта</span>
                                <button id="deleteCard" class="btn btn-sm btn-light" data-toggle="modal"
                                        data-target="#deleteCardModal">
                                    Привязать карту
                                </button>
                            </div>
                            <div style="z-index: 2">
                            <span class="text-muted fa-stack fa-1x">
                                <i class="far fa-credit-card fa-stack-1x icon-credit-card"></i>
                                <i class="fas fa-slash fa-stack-2x "></i>
                            </span>
                                <span><?= date('d.m', strtotime('+1 day', $companyTariff['payday'])); ?> ваш тарифный план изменится на Бесплатный</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <span class="d-block text-muted bg-icon-ruble">
                <i class="fas fa-ruble-sign icon-ruble"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<?php if ($companyTariff['tariff'] == 0): ?>

    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="row">
                    <div class="col-12 col-lg-5 card-tariff-left">
                        <div class="card-body">
                            <span class="small text-muted">Ваш тарифный план</span>
                            <h2>Бесплатный</h2>
                            <p>
                                <span class="small text-muted">Безграничный период <i
                                            class="fas fa-infinity"></i></span>
                            </p>
                            <div class="d-flex">
                                <input class="form-control text-muted" id="promoInput" placeholder="Введите промокод"
                                       type="text">
                                <button class="btn btn-primary" id="promoBtn">
                                    Применить
                                </button>
                            </div>
                            <span class="small text-muted">Если вы где-то нашли промокод, скорее вводите и получайте бонусы 🌟</span>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12 card-tariff-right">
                        <div class="card-body">
                            <span class="small text-muted"> Ограничения по тарифу</span>
                            <br>
                            <span><i class="fas fa-times text-muted"></i> В хранилище файлов свободно <?= normalizeSize($remainingLimits['space'])['size'] ?> <?= normalizeSize($remainingLimits['space'])['suffix'] ?> из 100 МБ</span>
                            <br>
                            <span><i class="fas fa-times text-muted"></i> Осталось <?= $remainingLimits['tasks'] ?> из 150 задач в этом месяце</span>
                            <br>
                            <span><i class="fas fa-times text-muted"></i> Отсутствие отчетов</span>
                            <br>
                            <span><i class="fas fa-times text-muted"></i> Отсутствие интеграции с облаком</span>
                        </div>
                    </div>
                    <span class="d-block text-muted bg-icon-ruble">
                <i class="fas fa-ruble-sign icon-ruble"></i>
                </span>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row mb-3">
        <div class="col">
            <div class="card">
                <div class="row">
                    <div class="col-12 col-lg-5 card-tariff-left">
                        <div class="card-body">
                            <span class="small text-muted">Ваш тарифный план</span>
                            <h2><?= $tariffInfo['tariff_name'] ?></h2>
                            <input type="hidden" id="currentTariff" value="<?= $companyTariff['tariff'] ?>">
                            <input type="hidden" id="isCardBinded" value="<?= $companyTariff['is_card_binded'] ?>">
                            <p>
                                <span class="small text-muted">Оплачено до <?= date('d.m', $companyTariff['payday']); ?></span>
                            </p>
                            <div class="d-flex">
                                <input class="form-control text-muted" id="promoInput" placeholder="Введите промокод"
                                       type="text">
                                <button class="btn btn-primary" id="promoBtn">
                                    Применить
                                </button>
                            </div>
                            <span class="small text-muted">Если вы где-то нашли промокод, скорее вводите и получайте бонусы 🌟</span>
                        </div>
                    </div>
                    <div class="col-lg-7 col-12 card-tariff-right">
                        <div class="card-body">
                            <?php if ($companyTariff['is_card_binded']): // Если привязана карта?>
                                <div class="d-flex" style="justify-content: space-between">
                                    <span class="small text-muted mt-1">Ваша банковская карта</span>
                                    <button id="deleteCard" class="btn btn-sm btn-light" data-toggle="modal"
                                            data-target="#deleteCardModal">
                                        Отвязать карту
                                    </button>
                                </div>
                                <div style="z-index: 2">
                            <span class="text-muted">
                            <i class="far fa-credit-card icon-credit-card"></i>
                            </span>
                                    <span><?= date('d.m', $companyTariff['payday']); ?> будет списание с карты <?= $companyTariff['pan']; ?> в размере <?= $tariffInfo['price'] / 100; ?> руб.</span>
                                </div>
                            <?php else: // Если не привязана карта ?>
                                <div style="z-index: 2">
                            <span class="text-muted fa-stack fa-1x">
                                <i class="far fa-credit-card fa-stack-1x icon-credit-card"></i>
                                <i class="fas fa-slash fa-stack-2x "></i>
                            </span>
                                    <span><?= date('d.m', strtotime('+1 day', $companyTariff['payday'])); ?> ваш тарифный план изменится на Бесплатный</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="d-block text-muted bg-icon-ruble">
                <i class="fas fa-ruble-sign icon-ruble"></i>
                </span>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<h5 class="font-weight-bold mb-4">Тарифный план</h5>
<div class="row">
    <?php foreach ($tariffList as $tariff): ?>
        <?php if ($tariff['tariff_id'] == 0) continue; ?>
        <div class="col-sm-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h3 class="font-weight-bold"><?= $tariff['tariff_name']; ?></h3>
                    <p>
                        <span class="text-secondary">Периодичность оплаты<br><?= $tariff['period_in_months']; ?> <?= ngettext('month', 'months', $tariff['period_in_months']); ?> </span>
                        - <?= $tariff['price'] / (100 * $tariff['period_in_months']); ?> руб./мес.</p>
                    <button class="btn btn-secondary choose-tariff" data-price="<?= $tariff['price'] / 100; ?>"
                            data-price-per-month="<?= $tariff['price'] / (100 * $tariff['period_in_months']); ?>"
                            data-period="<?= $tariff['period_in_months']; ?> <?= ngettext('month', 'months', $tariff['period_in_months']); ?>"
                            data-tariff-name="<?= $tariff['tariff_name']; ?>"
                            data-tariff-id="<?= $tariff['tariff_id']; ?>">Подробнее
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<p><strong>Внимание!</strong> Оплата тарифного плана происходит путем автоплатежа - автоматического
    списания суммы средств с периодичностью, соответствующей выбранному тарифу. Подписку можно отменить в любой момент.
</p>
<p>Нажимая кнопки "Сменить тариф", "Продлить подписку" или "Привязать карту для оплаты", вы подтверждаете, что
    ознакомились с понятием "автоплатеж" и с <a
            href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf" class="btn-link"
            target="_blank">Офертой рекуррентных платежей</a>.</p>
<hr>
<h5 class="font-weight-bold mb-3 mt-3">Операции</h5>
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
    <div class="modal-dialog d-flex modal-dialog-tariff" role="document">
        <div>
            <div class="modal-content border-0 left-modal text-white pt-4 pb-4">
                <div class="modal-header border-0 text-center d-block">
                    <h4 class="modal-title" id="exampleModalLabel">Новые возможности для вашего бизнеса</h4>
                </div>
                <div class="modal-body text-left">
                    <h5 class="mt-1 mb-3">Платный тариф - <span id="descriptionPrice"></span> рублей/месяц</h5>
                    <p><i class="fas fa-check"></i> Всё, что есть в бесплатном тарифе</p>
                    <p><i class="fas fa-check"></i> Неограниченное количество задач</p>
                    <p><i class="fas fa-check"></i> Бесшовная интеграция с Google Drive и DropBox + 1гб на нашем сервере
                    </p>
                    <p><i class="fas fa-check"></i> Подробная отчетность о деятельности компании и отдельных сотрудниках
                    </p>
                    <p><i class="fas fa-check"></i> Интеграции со сторонними сервисами, н-р AmoCRM, Яндекс.Метрика и
                        т.д.
                    </p>
                </div>
            </div>
        </div>

        <div>
            <div class="modal-content right-modal border-0 pt-4">
                <div class="modal-header border-0 text-center d-block">
                    <h5 class="modal-title" id="exampleModalLabel">Тарифный план "<span id="tariffName"></span>"</h5>
                </div>
                <?php if ($companyTariff['tariff'] == 0): ?>
                    <div class="modal-body text-left">
                        <p>Вы собираетесь оформить платную подписку:</p>
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
                            <p>Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                        <?php else: ?>
                            <p>Вы еще не использовали платный тариф - дарим вам 14 дней бесплатно</p>
                            <p>Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                        <?php endif; ?>
                        <p class="oferta-field"><input type="checkbox" id="oferta"
                                  style=" position: relative; top: 7px; margin-right: 10px; ">Я
                            согласен с <a
                                    href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                                    class="btn-link" target="_blank">Офертой рекуррентных платежей</a>.</p>
                        <hr>
                        <span class="position-absolute" id="disabledBtn" style="width: 92%; height: 8%; z-index: 100000; opacity: 0">
                            adasd
                        </span>
                        <button class="btn btn-secondary w-100" id="pay" disabled style="height: 38px">
                            Оплатить подписку
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
                        <p>Вы собираетесь сменить тариф</p>
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
                            <p>Для оформления подписки мы спишем с вашей карты 1 рубль и вернём его</p>
                        <?php endif; ?>
                        <p><input type="checkbox" id="oferta"
                                  style=" position: relative; top: 7px; margin-right: 10px; ">Я
                            согласен с <a
                                    href="https://lusy.io/licenzionnoe-soglashenie-dogovor-publichnoj-oferty.pdf"
                                    class="btn-link" target="_blank">Офертой рекуррентных платежей</a>.</p>
                        <hr>
                        <button class="btn btn-secondary w-100" id="changeTariff" disabled>
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
        </div>
        <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
        </span>
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
                            location.reload();
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

        $('#payModal').on('hide.bs.modal', function () {
            $('#oferta').prop("checked", false);
            $('#pay').attr('disabled', true).addClass('btn-secondary').removeClass('btn-primary').text('Оплатить подписку');
            $('#changeTariff').attr('disabled', true).addClass('btn-secondary').text('Сменить тариф');
            $('#oferta').attr('disabled', false);
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


    });
</script>