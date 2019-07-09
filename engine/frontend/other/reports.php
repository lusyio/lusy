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
                            <option value="<?= $n['id'] ?>"><span><?= $n["name"] ?> <?= $n["surname"] ?></option>
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

<div class="card mt-3 report-container" style="display: none">
    <div class="card-body">
        <div class="row text-muted text-center">
            <div class="col-8">
                <span class="small">Выполнено</span>
            </div>
            <div class="col-4">
                <span class="small">Просрочено</span>
            </div>
        </div>
        <div class="row text-center">
            <div class="col">
                <span class="badge badge-primary done-outcome" data-toggle="tooltip" data-placement="bottom"
                      title="<?= $GLOBALS['_outbox'] ?>"></span>
            </div>
            <div class="col">
                <span class="badge badge-dark done-income" data-toggle="tooltip" data-placement="bottom"
                      title="<?= $GLOBALS['_inbox'] ?>">></span>
            </div>
            <div class="col">
                <span class="overdue"></span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <div class="text-muted mb-2">Задачи за выбранный период:</div>
                <div class="tasks-list-report-empty">
                </div>
                <div class="tasks-list-report">

                </div>
            </div>
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
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    $.when($('.total-report').fadeOut(300)).done(function () {
                        $('.report-container').show();
                        $('.done-income').html(data.doneIncome);
                        $('.done-outcome').html(data.doneOutcome);
                        $('.overdue').html(data.overdue);
                        var color;
                        var status;

                        console.log(data.tasks);
                        if (data.tasks.length === 0) {
                            $('.tasks-list-report').html('');
                            $('.tasks-list-report-empty').html('<div class="task-card">\n' +
                                '    <div class="card">\n' +
                                '        <div class="card-body tasks-list">\n' +
                                '            <div class="row text-center">\n' +
                                '                <div class="col">\n' +
                                '                    <span class="taskname text-muted">\n' +
                                '                        Задач за выбранный период не найдено\n' +
                                '                    </span>\n' +
                                '                </div>\n' +
                                '            </div>\n' +
                                '        </div>\n' +
                                '    </div>\n' +
                                '</div>')
                        } else {
                            $('.tasks-list-report-empty').html('');
                            $('.tasks-list-report').html('');
                            data.tasks.forEach(function (e, i) {

                                if (e.status === 'done') {
                                    color = 'border-success';
                                    status = 'Завершена'
                                } else {
                                    if (e.status === 'postpone') {
                                        color = 'border-warning';
                                        status = 'Перенос срока'
                                    } else {
                                        if (e.status === 'inwork') {
                                            color = 'border-primary';
                                            status = 'В работе'
                                        } else {
                                            if (e.status === 'pending') {
                                                color = 'border-warning';
                                                status = 'На рассмотрении'
                                            }
                                        }
                                    }
                                }
                                $('.tasks-list-report').append('<a class="text-decoration-none cust" href=\' /task/' + e.id + '/ \'>\n' +
                                    '    <div class="task-card">\n' +
                                    '       <div class="card mb-2 tasks">\n' +
                                    '           <div class="card-body tasks-list">\n' +
                                    '               <div class=\'d-block border-left-tasks ' + color + ' ' + e.status + ' \'>\n' +
                                    '                   <div class="row">\n' +
                                    '                       <div class="col-9">\n' +
                                    '                           <div>\n' +
                                    '                               <span class="taskname"> ' + e.name + ' </span>\n' +
                                    '                           </div>\n' +
                                    '                       </div>\n' +
                                    '                           <div class="col-3 p-0 text-center">\n' +
                                    '                               <span> ' + status + ' </span>\n' +
                                    '                           </div>\n' +
                                    '                   </div>\n' +
                                    '               </div>\n' +
                                    '           </div>\n' +
                                    '        </div>\n' +
                                    '    </div>\n' +
                                    '</a>');

                            });
                        }
                    });
                    console.log(data);
                }
            });
        });

    });
</script>
