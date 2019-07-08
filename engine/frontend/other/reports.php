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
<link href="/assets/css/datatables.min.css" rel="stylesheet">
<script type="text/javascript" src="/assets/js/datatables.min.js"></script>
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

<form id="create-report" method="post">
    <div class="card mt-3">
        <div class="card-body">
            <h5>
                Построение отчета
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
                           id="endReportDate">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12 col-lg-6">
                    <label>
                        Выберите сотрудника
                    </label>
                    <select class="custom-select custom-select-sm" id="workerReport">
                        <?php
                        require_once __ROOT__ . '/engine/backend/other/company.php';
                        foreach ($sql as $n):?>
                            <option val="<?= $n['id'] ?>"><span><?= $n["name"] ?> <?= $n["surname"] ?></option>
                            <?php
                            ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col text-center">
                    <button class="btn btn-primary create-report">Построить отчет</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="card mt-3 total-report">
    <div class="card-body">
        <table id="dtOrderExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
            <thead>
            <tr class="text-center text-muted">
                <th class="th-sm">ФИО</th>
                <th class="th-sm">В работе</th>
                <th class="th-sm">Просрочено</th>
                <th class="th-sm">Выполнено</th>
            </tr>
            </thead>
            <tbody>

            <?php
            require_once __ROOT__ . '/engine/backend/other/company.php';
            foreach ($sql

            as $n):
            $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
            $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>
            <tr>
                <td><span><?= $n["name"] ?> <?= $n["surname"] ?></td>
                <td class="text-center"><?= $inwork ?></td>
                <td class="text-center"><?= $overdue ?></td>
                <td class="text-center">
                    <span class="badge badge-primary"><?= $n['doneAsManager'] ?></span>
                    <span class="badge badge-dark"><?= $n['doneAsWorker'] ?></span>
                </td>
                <?php
                endforeach;
                ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-3 report-container" style="display: none;">
    <div class="card-body">
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
                    <div class="row">
                        <div class="col">
                            <span class="text-muted">Задачи за выбранный период:</span>
                            <div class="task-list-report">
                                <a href="#">
                                    <div class="task-card">
                                        <div class="card mb-2 tasks">
                                            <div class="card-body tasks-list">
                                                <div class="d-block border-left-tasks border-warning">
                                                    <p class="font-weight-light text-ligther d-none">Завершено</p>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div>
                                                                <span class="taskname">Написать программу вебинара</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endforeach; ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#dtOrderExample').DataTable({
            "order": [[3, "desc"]]
        });
        $('.dataTables_length').addClass('bs-select');

        $('.create-report').on('click', function (e) {
            e.preventDefault();
            var startDate = $('#startReportDate').val();
            var endDate = $('#endReportDate').val();
            var workerId = $('#workerReport').val();
            console.log(startDate);
            console.log(endDate);
            console.log(workerId);
            var fd = new FormData();
            fd.append('ajax', 'report');
            fd.append('module', 'personalStat');
            fd.append('workerId', workerId);
            fd.append('startDate', startDate);
            fd.append('endDate', endDate);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    $.when($('.total-report').fadeOut(300)).done(function () {
                        $('.report-container').show().html(data).fadeIn();
                    });
                    console.log(data);
                }
            });
        });

    });
</script>
