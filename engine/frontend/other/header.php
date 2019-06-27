<!doctype html>
<?php if (empty($langc) or empty($title)) {
    $langc = 'test';
    $title = 'test';
} ?>
<html lang="<?= $langc ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/custom.css?ver=10">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
            integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
            crossorigin="anonymous"></script>

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
            <div id="workzone" class="pb-3">
                <?php
                inc('main', 'workzone');
                ?>
            </div>
        </div>
    </div>
</div>
<div class="push-messages-area d-none"></div>
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
        checkNotifications('onLoad');
        $('.push-messages-area').on('click', '.close-push-message', function (e) {
            e.preventDefault();
            $(this).parents('.push-message').fadeOut(300);
        });

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

        })
    })
</script>
<?php }
	