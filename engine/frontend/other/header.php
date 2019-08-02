<!doctype html>
<?php if (empty($langc)){
    $langc = 'ru';
}
if (empty($title)) {
    $title = 'empty';
} ?>
<html lang="<?= $langc ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css?ver=19">
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="/assets/js/popper.min.js"></script>
    <title><?= $title ?></title>
</head>
<body class="anim-show">
<?php if (!empty($_SESSION['auth'])) { ?>
<?php inc('nav', 'top-sidebar'); ?>
<div class="container">
    <div class="row">
        <div class="col-sm-3 navblock pt-3">
            <?php
            inc('nav', 'main-nav');
            ?>
        </div>
        <div class="col-sm-9">
            <div id="workzone" class="pb-3 pr-0">
                <?php if ($roleu == 'ceo' && !$isCompanyActivated): ?>
                    <div class="alert alert-warning alert-dismissible fade show" id="activateAlert" role="alert">
                        <span id="activateText"><strong>Подтвердите e-mail</strong> <a href="#" id="sendActivation"> Нажмите сюда</a>, чтобы еще раз отправить письмо c ссылкой активации</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <div id="activateSpinner" class="spinner-border spinner-border-sm text-warning m-1 d-none" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <span aria-hidden="true">&times;</span>
                        </button>

                    </div>
                <?php endif; ?>
                <?php
                inc('main', 'workzone');
                ?>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        var userId = <?= $id ?>;
        cometApi.start({dev_id: 2553, user_id: userId, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        cometApi.onAuthSuccess(function(){
            console.log("Подключились и авторизовались успешно")
        });

// Добавление callBack функции на уведомление об не успешной авторизации
        cometApi.onAuthFalill(function(){
            console.log("Подключились успешно но не авторизовались")
        });
        getCounters(function (data) {
            setCounters(data);
            $('#counters a').removeClass('d-none');
        });
        subscribeToMessagesNotification(userId);
        subscribeToOnlineStatusNotification('<?=$cometTrackChannelName?>');
        subscribeToChatNotification('<?=$cometTrackChannelName?>');

        $('#search').on('keyup', function () {
            var request = $('#search').val();
            console.log(request);
            if (request) {
                var fd = new FormData();
                fd.append('ajax', 'search');
                fd.append('request', request);
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',

                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        console.log(JSON.parse(data));
                    },
                });
            }
        });

        <?php if ($roleu == 'ceo' && !$isCompanyActivated): ?>
        $('#sendActivation').on('click', function (e) {
            e.preventDefault();
            $('#activateSpinner').removeClass('d-none');
            var fd = new FormData();
            fd.append('module', 'sendActivation');
            fd.append('ajax', 'company');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $('#activateSpinner').addClass('d-none');
                    $('#activateText').html('<strong>Письмо отправлено</strong> Перейдите по ссылке в письме');
                    $('#activateAlert').addClass('alert-success').removeClass('alert-warning');
                    setTimeout(function () {
                        $('#activateAlert').alert('close');
                    }, 3000)
                },
            });
        });
        <?php endif; ?>

    })
</script>
<?php }
	