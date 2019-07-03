<div class="card mb-3">
    <div class="card-body text-center">
        <h2 class="d-inline text-uppercase font-weight-bold">
            Demo
        </h2>
        <div>
            <?php if ($tariff == 0):?>
            <span class="badge badge-primary mt-3"><?=$_free?></span>
            <?php endif; ?>
            <?php if ($tariff == 1):?>
            <span class="badge badge-dark mt-3"><?=$_premium?></span>
            <?php endif; ?>
        </div>
        <div class="icon-edit-profile">
            <a data-toggle="tooltip" data-placement="bottom" title="" href="/company-settings/"
               data-original-title="Настройки компании"><i id="editProfile" class="fas fa-cog edit-profile"></i></a>
        </div>
        <div class="info-company">
            <hr>
            <div class="row text-left">
                <div class="col-12 col-lg-9">
                    <div class="about-company">
                        <div class="mb-1">Описание:</div>
                        <div style="color: #c6c9dc;">Отсутствует</div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="site-company">
                        <div class="mb-1">Сайт:</div>
                        <div style="color: #c6c9dc;">https://</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="padding: 0.8rem;" class="d-sm task-box">
    <div style="padding-left: 7px;">
        <div class="row sort">
            <div class="col-5">
                <span>Имя сотрудника</span>
            </div>
            <div class="col-2 text-center">
                <span>В работе</span>
            </div>
            <div class="col-2 text-center">
                <span>Просрочено</span>
            </div>
            <div class="col-2 text-center">
                <span>Выполнено</span>
            </div>
            <div data-toggle="tooltip" data-placement="bottom" title="Статистика за месяц">
                <i class="fas fa-info-circle icon-company"></i>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <?php
    foreach ($sql

             as $n):
        $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
        $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')');
        if ($n['is_fired'] != 0) {
            $isFired = true;
        } else {
            $isFired = false;
        }
        if ($isFired && !$isFiredShown):
            $isFiredShown = true; ?>
            <a href="#" id="showFired" class="text-decoration-none text-center">
                <div class="card-body border-bottom-company">
                    <?= $GLOBALS['_firedcompany'] ?>
                </div>
            </a>
        <?php endif; ?>
        <div class="card-body border-bottom-company <?= ($isFired) ? 'fired d-none text-muted' : '' ?>">
            <div class="row">
                <div class="col-lg-1 col-2">
                    <div class="user-pic position-relative" style="width:45px">
                        <a href="/profile/<?= $n['id'] ?>/">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-img rounded-circle w-100"/>
                            <span class="online-indicator-company">
                                <i class="fas fa-circle mr-1 ml-1 onlineIndicator company <?= ($n['online']) ? 'text-success' : '' ?>"></i>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-10 col-sm-4">
                    <?php
                    if ($n["name"] != null && $n["surname"] != null): ?>
                        <p class="h5 mb-0 company-profile-fio">
                            <a href="/profile/<?= $n["id"] ?>/"><?= $n["name"] ?> <?= $n["surname"] ?></a>
                        </p>
                        <span class="text-muted-reg activity-company"><?= ($n['online']) ? $GLOBALS['_online'] : ((isset($n['activity'])) ? $GLOBALS['_wasOnline'] . ' ' . date('d.m H:i', $n['activity']) : '') ?></span>
                    <?php
                    else: ?>
                        <p class="h5 mb-0 company-profile-fio">
                            <a href="/profile/<?= $n["id"] ?>/">
                                <?= $n['email'] ?>
                            </a>
                        </p>
                        <span class="text-muted-reg activity-company">
                            <?= ($n['online']) ? $GLOBALS['_online'] : ((isset($n['activity'])) ? $GLOBALS['_wasOnline'] . ' ' . date('d.m H:i', $n['activity']) : '') ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-4 col-sm-2 text-center">
                    <div class="count-company-tasks" style="margin-top: 15%;">
                        <?= $inwork ?>
                    </div>
                    <small class="text-muted company-tasks">В работе</small>
                </div>
                <div class="col-4 col-sm-2 text-center">
                    <div class="count-company-tasks" style="margin-top: 15%;"><?= $overdue ?></div>
                    <small class="text-muted company-tasks">Просрочено</small>
                </div>
                <div class="col-4 col-sm-2 text-center">
                    <div class="count-company-tasks" style="margin-top: 15%;">
                        <span class="badge badge-company-primary"><?= $n['doneAsManager'] ?></span>
                        <span class="badge badge-company-dark"><?= $n['doneAsWorker'] ?></span>
                    </div>
                    <small class="text-muted company-tasks">Выполнено</small>
                </div>
                <div class="position-absolute" style="right: 8px">
                    <?php if ($isCeo): ?>
                        <div class="d-none">
                            <a href="/settings/">
                                <i id="editProfile" class="fas fa-pencil-alt edit-profile"></i>
                            </a>
                        </div>
                        <?php if ($isFired && $n['id'] != $id): ?>
                            <div class="mt">
                                <a href="#" title="" data-user-id="<?= $n['id'] ?>" class="restore-user">
                                    <i class="fas fa-user-check edit-profile"></i>
                                </a>
                            </div>
                        <?php elseif ($n['id'] != $id): ?>
                            <div class="mt">
                                <a href="#" data-user-id="<?= $n['id'] ?>" class="fire-user" data-toggle="tooltip"
                                   data-placement="bottom" title="<?= $GLOBALS['_fireoutcompany'] ?>">
                                    <i class="fas fa-user-slash edit-profile"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (!$isFiredShown):
        $isFiredShown = true; ?>
        <a href="#" id="showFired" class="text-decoration-none text-center d-none">
            <div class="card-body border-bottom-company">
                <?= $GLOBALS['_firedcompany'] ?>
            </div>
        </a>
    <?php endif; ?>
</div>
<?php if ($isCeo): ?>
    <div class="row">
        <div class="col mt-4 text-center">
            <a class="btn btn-primary d-inline" href="/invite/">
                <i class="fas fa-user-plus text-white mr-1"></i> <?= $_buttonInvateNew ?>
            </a>
        </div>
    </div>
<?php endif; ?>
<style>
    .card-body {
        -webkit-transition: background-color 0.5s;
        -moz-transition: background-color 0.5s;
        -ms-transition: background-color 0.5s;
        -o-transition: background-color 0.5s;
        transition: background-color 0.5s;
    }
</style>
<script>

    $(document).ready(function () {

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        var $firedButton = $('#showFired');


        $firedButton.on('click', function (e) {
            e.preventDefault();
            if ($firedButton.hasClass('show')) {
                $(this).removeClass('show');
                $firedButton.nextAll('.card-body').addClass('d-none');
            } else {
                $(this).addClass('show');
                $firedButton.nextAll('.card-body').removeClass('d-none');
                $('html, body').animate({
                    scrollTop: $firedButton.offset().top - 100
                }, 1000);
            }
        });

        $('#workzone').on('click', '.fire-user', function (e) {
            e.preventDefault();
            var el = $(this);
            var userId = el.data('user-id');
            var userCard = el.closest('.card-body');
            var fd = new FormData();
            fd.append('module', 'fireUser');
            fd.append('ajax', 'company');
            fd.append('userId', userId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $firedButton.after(userCard);
                    $firedButton.removeClass('d-none').addClass('show');
                    $firedButton.nextAll('.card-body').removeClass('d-none');
                    el.removeClass('fire-user').addClass('restore-user');
                    el.children().removeClass('fa-user-slash').addClass('fa-user-check');
                    userCard.addClass('text-muted').addClass('fired').css({'background-color': '#dbe7f6'});
                    $('html, body').animate({
                        scrollTop: userCard.offset().top - 100
                    }, 1000);
                    setTimeout(function () {
                        userCard.attr('style', '');
                    }, 3000);
                },
            });
        });


        $('#workzone').on('click', '.restore-user', function (e) {
            e.preventDefault();
            var el = $(this);
            var userId = el.data('user-id');
            var userCard = el.closest('.card-body');
            console.log(userId);
            var fd = new FormData();
            fd.append('module', 'restoreUser');
            fd.append('ajax', 'company');
            fd.append('userId', userId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $firedButton.before(userCard);
                    el.removeClass('restore-user').addClass('fire-user');
                    el.children().removeClass('fa-user-check').addClass('fa-user-slash');
                    userCard.removeClass('text-muted').removeClass('fired').css({'background-color': '#dbe7f6'});
                    $('html, body').animate({
                        scrollTop: userCard.offset().top - 100
                    }, 1000);
                    setTimeout(function () {
                        userCard.attr('style', '');
                    }, 3000);
                    if ($('.fired').length === 0) {
                        $('#showFired').addClass('d-none');
                    }
                },
            });

        })
    })
</script>