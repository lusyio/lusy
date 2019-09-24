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
    </div>
</div>
<div class="card partner-card partner-progress text-white mt-4">
    <div class="card-body">
        <?php if (!$infinitePremium): ?>
            <div class="row">
                <div class="col">
                    <h5 class="text-center text-white">
                        Получи Premium навсегда!
                    </h5>
                    <p class="partner-progress-description">
                        Привлеки 30 друзей в систему, и мы отблагодарим Вашу компанию вечным Premium-тарифом
                    </p>
                    <div class="mt-4">
                        <div class="card-body">
                            <div class="row row-hr-partner text-center text-white position-relative">
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
            <div class="col-12">
                <h5 class="text-center">
                    Подзравляем!<br>
                    У вас теперь бесконечная подписка
                </h5>
            </div>
            <div class="col-12">
                <img src="/upload/undraw_winners.svg" alt="">
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
            <i class="fas fa-check text-success paymentIcon"></i>
        </div>
        <div class="col-10 col-sm-5 col-lg-9">
            <p class="m-0">
                <?= $company['idcompany'] ?>
            </p>
            <p class="m-0 text-secondary">
                <?= date('d.m.Y H:i',$company['datareg']) ?>
            </p>
        </div>
        <div class="text-success d-none d-sm-block col-12 col-sm-4 col-lg-2 text-center">
            Успешно
        </div>
    </div>
</div>
<?php endforeach; ?>
<script src="/assets/js/clipboard.min.js"></script>
<script>
    new ClipboardJS('.copy-partner-link');
</script>
