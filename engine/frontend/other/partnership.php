<div class="card partner-card">
    <div class="card-body">
        <h5>Ваша реферальная ссылка:</h5>
        <div class="partner-link">
            <span>
                <i data-clipboard-text="<?= $personalPromocodeLink ?>"
                   class="fas fa-link copy-partner-link" data-toggle="tooltip" data-placement="bottom" title=""
                   data-original-title="Скопировать ссылку">
                </i>
            </span>
            <a class="text-decoration-none" href="#"><?= $personalPromocodeLink ?></a>
        </div>
    </div>
</div>

<div class="card partner-card mt-4">
    <div class="card-body">
        <h5>Ваш промокод для друзей:</h5>
        <div class="partner-link">
            <span>
                <i data-clipboard-text="<?= $personalPromocode ?>"
                   class="far fa-copy copy-partner-link" data-toggle="tooltip" data-placement="bottom" title=""
                   data-original-title="Скопировать промокод">
                </i>
            </span>
            <span><?= $personalPromocode ?></span>
        </div>
        <div class="row mt-5">
            <div class="col-12 col-sm-4 text-center">
                <span class="partner-icon">
                    <i class="fas fa-ad"></i>
                </span>
                <a class="text-decoration-none" target="_blank" href="https://lusy.io/ru/partners/#waystab">Способы продвижения</a>
            </div>
            <div class="col-12 col-sm-4 text-center">
                <span class="partner-icon">
                    <i class="fas fa-bullhorn"></i>
                </span>
                <a class="text-decoration-none" target="_blank" href="https://lusy.io/ru/partners/#marketingtab">Рекламные материалы</a>
            </div>
            <div class="col-12 col-sm-4 text-center">
                <span class="partner-icon">
                    <i class="fas fa-book-reader"></i>
                </span>
                <a class="text-decoration-none" target="_blank" href="https://lusy.io/ru/partners/#rulestab">Правила программы</a>
            </div>
        </div>
    </div>
</div>

<div class="card partner-card mt-4">
    <div class="card-body">
        <h5>
            Статистика:
        </h5>
        <div class="row mt-5">
            <div class="col-12 col-sm-4 text-center">
                <p class="text-success partner-stat-number">
                    <?= count($invitedCompanies) ?>
                </p>
                <p class="partner-stat-name">
                    Всего зарегистрировано
                </p>
            </div>
            <div class="col-12 col-sm-4 text-center">
                <p class="partner-stat-number">
                    <span class="badge badge-warning"><?= $waitingForApprove; ?></span>
                    <span class="badge badge-success"><?= $approved; ?></span>
                </p>
                <p class="partner-stat-name">
                    Ожидает подтверждение/Одобренные
                </p>
            </div>
            <div class="col-12 col-sm-4  text-center">
                <p class="partner-stat-bonus partner-stat-number">
                    +<?= $waitingForApprove * 14 . ' ' . ngettext('day', 'days', $waitingForApprove * 14) ?>
                </p>
                <p class="partner-stat-name">
                    Ожидаемый бонус
                </p>
            </div>
        </div>
        <?php if (!$infinitePremium): ?>
        <div class="row mt-5">
            <div class="col">
                <h5 class="text-center">
                    Получи Premium навсегда!
                </h5>
                <p class="partner-progress-description">
                    Привлеки 30 друзей в систему, и мы отблагодарим Вашу компанию вечным Premium-тарифом
                </p>
                <div class="card partner-progress mt-4">
                    <div class="card-body">
                        <div class="row text-center text-white position-relative">
                            <div class="partner-hr progress">
                                <div class="progress-bar" role="progressbar" style="width: <?= $progressBarValue ?>%" aria-valuenow="<?= $progressBarValue ?>%"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="col">
                                <i class="fas fa-dot-circle dot-partner"></i>
                                <p class="mb-0 mt-2 small">0 компаний</p>
                            </div>
                            <div class="col">
                                <i class="<?= ($approved >= 5) ? 'fas' : 'far'?> fa-dot-circle dot-partner"></i>
                                <p class="mb-0 mt-2 small">5 компаний</p>
                            </div>
                            <div class="col">
                                <i class="<?= ($approved >= 10) ? 'fas' : 'far'?>  fa-dot-circle dot-partner"></i>
                                <p class="mb-0 mt-2 small">10 компаний</p>
                            </div>
                            <div class="col">
                                <i class="<?= ($approved >= 20) ? 'fas' : 'far'?>  fa-dot-circle dot-partner"></i>
                                <p class="mb-0 mt-2 small">20 компаний</p>
                            </div>
                            <div class="col">
                                <i class="fas fa-crown dot-partner"></i>
                                <p class="mb-0 mt-2 small">30 компаний</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php if ($infinitePremium): ?>
<div class="card partner-card mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-5">
                <h5 class="partner-mt">
                    Подзравляем! Вам доступна бесконечная подписка
                </h5>
            </div>
            <div class="col-12 col-md-7">
                <img src="/upload/winner-partner.jpg" alt="">
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if (count($invitedCompanies) > 0 ): ?>
<h5 class="mt-3 mb-3">
    Привлеченные компании:
</h5>
<?php endif; ?>
<?php foreach ($invitedCompanies as $company): ?>
<div class="card mb-1 payment-card partner-card pt-1 pb-1">
    <div class="card-body row">
        <div class="col-2 col-sm-2 col-lg-1">
            <i class="<?= ($company['promo_status'] == 1) ? 'fas fa-check text-success' : 'fas fa-clock text-warning' ?> paymentIcon"></i>
        </div>
        <div class="col-10 col-sm-5 col-lg-8">
            <p class="m-0">
                <?= $company['idcompany'] ?>
            </p>
            <p class="m-0 text-secondary">
                <?= date('d.m.Y H:i',$company['datareg']) ?>
            </p>
        </div>
        <div class="<?= ($company['promo_status'] == 1) ? 'text-success' : 'text-warning' ?> d-none d-sm-block col-12 col-sm-4 col-lg-3 text-center">
            <?= ($company['promo_status'] == 1) ? 'Успешно' : 'Ожидает подтверждения' ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
<script src="/assets/js/clipboard.min.js"></script>
<script>
    new ClipboardJS('.copy-partner-link');
</script>
