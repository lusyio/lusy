<div class="card mb-3">
    <div class="card-body text-center">
        <div>
            <h2 class="d-inline text-uppercase font-weight-bold"><?= $namecompany ?></h2>
            <?php if ($isCeo): ?>
            <a class="d-inline float-right" href="/invite/"><i class="fas fa-user-plus icon-company"></i></a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="card">
    <?php
    foreach ($sql as $n):
        if ($n['is_fired'] != 0) {
            $isFired = true;
        } else {
            $isFired = false;
        }
        if ($isFired && !$isFiredShown):
            $isFiredShown = true; ?>
            <a href="#" id="showFired" class="text-decoration-none text-center">
                <div class="card-body border-bottom">
                    Уволенные сотрудники
                </div>
            </a>
        <?php endif; ?>
        <div class="card-body border-bottom <?= ($isFired) ? 'fired d-none text-muted' : '' ?>">
            <div class="row">
                <div class="col-sm-5">
                    <div class="d-flex">
                        <div class="user-pic position-relative" style="width:85px">
                            <a href="/profile/<?= $n[$id] ?>/"><img src="/<?=getAvatarLink($n["id"]) ?>" class="avatar-img rounded-circle w-100 mb-4"/></a></div>
                        <p class="ml-3 mt-4"><a
                                    href="/profile/<?= $n["id"] ?>/"><?= $n["name"] ?> <?= $n["surname"] ?></a></p>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="ml-3 mt-4">
                        <span>Phone : <?= $n['phone'] ?></span>
                        <br>
                        <span>Email : <?=$n['email'] ?></span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="ml-3 mt-4">
<!--                        <span><i class="fas fa-long-arrow-alt-up edit-profile"></i></span>-->
                    </div>
                </div>
                <div class="col-sm-1">
                    <?php if ($isCeo): ?>
                        <div class="float-right">
                            <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                        </div>
                        <?php if ($isFired && $n['id'] != $id): ?>
                        <div class="float-right mt-3">
                            <a href="#" title="" data-user-id="<?= $n['id'] ?>"
                               class="restore-user"><i class="fas fa-user-check edit-profile"></i></a>
                        </div>
                        <?php elseif ($n['id'] != $id): ?>
                        <div class="float-right mt-3">
                            <a href="#" title="" data-user-id="<?= $n['id'] ?>" class="fire-user"><i
                                        class="fas fa-user-slash edit-profile"></i></a>
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
        <div class="card-body border-bottom">
            Уволенные сотрудники
        </div>
    </a>
    <?php endif; ?>
</div>
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
    $(document).ready(function() {
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