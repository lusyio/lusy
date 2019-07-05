<!--<form method="post">-->
<!--    <button type="submit" class="btn btn-link mb-3 pl-0" name="send">Отправить на почту</button>-->
<!--</form>-->
<!--<div class="card">-->
<?php
//global $idc;
//$inviteeMail = 'mr-kelevras@yandex.ru';
//$template = 'company-welcome';
//
//require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
//require_once __ROOT__ . '/engine/phpmailer/Exception.php';
//$mail = new \PHPMailer\PHPMailer\LusyMailer();
//try {
//    $mail->addAddress($inviteeMail);
//
//    $mail->isHTML();
//    $mail->Subject = "Добро пожаловать в Lusy.io";
//    $companyName = DBOnce('idcompany', 'company', 'id=' . $idc);
//    $args = [
//        'companyName' => $companyName,
//    ];
//    $mail->setMessageContent($template, $args);
//} catch (Exception $e) {
//
//}
//
//if (isset($_POST["send"])) {
//    $mail->send();
//    echo 'отправлено';
//}
//
//include __ROOT__ . '/engine/phpmailer/templates/ru/content-header.php';
//include __ROOT__ . '/engine/phpmailer/templates/ru/'.$template.'.php';
//include __ROOT__ . '/engine/phpmailer/templates/ru/content-footer.php';
//?>
<!---->
<!---->
<!--</div>-->
<div class="card">
    <div class="card-body text-center">
        <h2 class="d-inline text-uppercase font-weight-bold">
            Demo
        </h2>
        <h5 class="text-center">
            Общая статистика
        </h5>
        <hr>
        <div class="row text-center">
            <div class="col">
                <p class="text-muted">Выполнено</p>
                <p>50</p>
            </div>
            <div class="col">
                <p class="text-muted">Просрочено</p>
                <p>12</p>
            </div>
            <div class="col">
                <p class="text-muted">Комментарии</p>
                <p>123</p>
            </div>
            <div class="col">
                <p class="text-muted">События</p>
                <p>300</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5>
            Настройка интервала построения отчета
        </h5>
        <div class="row">
            <div class="col">
                <label>
                    Дата начала:
                </label>
                <input type="date" class="form-control form-control-sm" value="<?= $GLOBALS["now"] ?>"
                       id="startReportDate">
            </div>
            <div class="col">
                <label>
                    Дата конца:
                </label>
                <input type="date" class="form-control form-control-sm" value="<?= $GLOBALS["now"] ?>"
                       id="startReportDate">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-center">
                <button class="btn btn-primary">Построить отчет</button>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="d-flex flex-wrap report-container">
        <?php
        require_once __ROOT__ . '/engine/backend/other/company.php';
        foreach ($sql as $n):
            $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
            $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>

            <div class="card-body border-bottom border-right report-card-worker">
                <a href="/profile/<?= $n['id'] ?>/" class="text-decoration-none">
                    <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar">
                </a>
                <a href="/profile/<?= $n['id'] ?>/">
                    <div class="d-inline ml-2"><span><?= $n["name"] ?> <?= $n["surname"] ?></span></div>
                </a>
                <span class="float-right icon-full-info-reports" style="color: #dadbdc; cursor: pointer;" data-toggle="tooltip"
                      data-placement="bottom" title="Полная информация">
                <i class="fas fa-ellipsis-h" style="font-size: 20px"></i>
                </span>
                <hr>
                <div class="row">
                    <div class="col">
                        <div class="text-muted">
                            <div>Выполнено</div>
                            <div>Просрочено</div>
                            <div>В работе</div>
                        </div>
                    </div>
                    <div class="col-3 col-lg-3 text-center">
                        <div class="count-company-tasks">
                            <span class="badge badge-company-primary"><?= $n['doneAsManager'] ?></span>
                            <span class="badge badge-company-dark"><?= $n['doneAsWorker'] ?></span>
                        </div>
                        <div><?= $overdue ?></div>
                        <div><?= $inwork ?></div>
                    </div>
                </div>
                <div class="row more-info-reports">
                    <div class="col">
                        <h6>
                            Подбробная информация о сотруднике:
                        </h6>
                        <span class="text-muted">
                            Последние задачи
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    $(document).ready(function () {
        var i = 1;
        $('.icon-full-info-reports').on('click', function () {
            i++;
            if (i % 2 === 0) {
                $('.report-card-worker').hide();
                $(this).parents('.report-card-worker').show();
                $('.report-card-worker:visible').find('.more-info-reports').show();
            } else{
                $('.more-info-reports').hide();
                $('.report-card-worker').show();
            }
        })
    });
</script>