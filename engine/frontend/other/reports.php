<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinysort/2.3.6/tinysort.min.js"></script>
<?php
global $tariff;
if ($tariff == 0): ?>
    <form id="">
        <div class="row">
            <div class="col-12 col-lg-6 top-block-tasknew">
                <label class="label-tasknew">
                    Тип отчета
                </label>
                <div class="mb-2 card card-tasknew">
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports" id="typeOfReport"
                            style="height: 50px">
                        <option value="1" selected><span>Отчет по компании</option>
                        <option value="2"><span>Отчет по сотруднику</option>
                    </select>
                    <!--                <input type="text" id="name" class="form-control border-0 card-body-tasknew"-->
                    <!--                       style="height: 50px;"-->
                    <!--                       placeholder="Выберите тип отчета"-->
                    <!--                       autocomplete="off" autofocus required>-->
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <label class="label-tasknew">
                    Дата начала
                </label>
                <div class="mb-2 card card-tasknew">
                    <input type="date" class="input-reports form-control border-0 card-body-reports"
                           style="height: 50px; font-size: 14px; padding-right: 20px !important;"
                           id="startReportDate"
                           value="<?= $GLOBALS["now"] ?>" required>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <label class="label-tasknew">
                    Дата окончания
                </label>
                <div class="mb-2 card card-tasknew">
                    <input type="date" class="input-reports form-control border-0 card-body-reports"
                           style="height: 50px; font-size: 14px;padding-right: 20px !important;"
                           id="endReportDate"
                           value="<?= $GLOBALS["now"] ?>" required>
                </div>
            </div>
        </div>

        <div class="row mt-25-tasknew" id="workerBlockReports" style="display: none">
            <div class="col-12 col-lg-6 top-block-tasknew">
                <div class="card card-tasknew">
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports" id="workerReport"
                            style="height: 50px">
                        <option value="" selected disabled>Выберите сотрудника</option>
                        <?php
                        require_once __ROOT__ . '/engine/backend/other/company.php';
                        foreach ($sql as $n):?>
                            <option value="<?= $n['id'] ?>"><span><?= $n["name"] ?> <?= $n["surname"] ?></option>
                            <?php
                            ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!--            <div class="mb-2 card card-tasknew">-->
                <!--                <input type="text" id="name" class="form-control border-0 card-body-tasknew"-->
                <!--                       style="height: 50px;"-->
                <!--                       placeholder="Выберите сотрудника"-->
                <!--                       autocomplete="off" autofocus required>-->
                <!--            </div>-->
            </div>
        </div>


        <div class="row" style="margin-top: 40px;margin-bottom: 60px;">
            <div class="col-10 col-lg-4">
                <button id="createReport"
                        class="btn btn-block btn-outline-primary h-100" style="margin-left: 30px;">Построить отчет
                </button>
            </div>
        </div>
    </form>
<?php endif; ?>
<?php if ($tariff == 1): ?>
<form id="">
    <div class="row">
        <div class="col-12 col-lg-6 top-block-tasknew">
            <label class="label-tasknew">
                Тип отчета
            </label>
            <div class="mb-2 card card-tasknew">
                <select class="input-reports custom-select custom-select-sm border-0 card-body-reports" id="typeOfReport"
                        style="height: 50px">
                    <option value="1" selected><span>Отчет по компании</option>
                    <option value="2"><span>Отчет по сотруднику</option>
                </select>
                <!--                <input type="text" id="name" class="form-control border-0 card-body-tasknew"-->
                <!--                       style="height: 50px;"-->
                <!--                       placeholder="Выберите тип отчета"-->
                <!--                       autocomplete="off" autofocus required>-->
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <label class="label-tasknew">
                Дата начала
            </label>
            <div class="mb-2 card card-tasknew">
                <input type="date" class="input-reports form-control border-0 card-body-reports"
                       style="height: 50px; font-size: 14px; padding-right: 20px !important;"
                       id="startReportDate"
                       value="<?= $GLOBALS["now"] ?>" required>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <label class="label-tasknew">
                Дата окончания
            </label>
            <div class="mb-2 card card-tasknew">
                <input type="date" class="input-reports form-control border-0 card-body-reports"
                       style="height: 50px; font-size: 14px;padding-right: 20px !important;"
                       id="endReportDate"
                       value="<?= $GLOBALS["now"] ?>" required>
            </div>
        </div>
    </div>

    <div class="row mt-25-tasknew" id="workerBlockReports" style="display: none">
        <div class="col-12 col-lg-6 top-block-tasknew">
            <div class="card card-tasknew">
                <select class="input-reports custom-select custom-select-sm border-0 card-body-reports" id="workerReport"
                        style="height: 50px">
                    <option value="" selected disabled>Выберите сотрудника</option>
                    <?php
                    require_once __ROOT__ . '/engine/backend/other/company.php';
                    foreach ($sql as $n):?>
                        <option value="<?= $n['id'] ?>"><span><?= $n["name"] ?> <?= $n["surname"] ?></option>
                        <?php
                        ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!--            <div class="mb-2 card card-tasknew">-->
            <!--                <input type="text" id="name" class="form-control border-0 card-body-tasknew"-->
            <!--                       style="height: 50px;"-->
            <!--                       placeholder="Выберите сотрудника"-->
            <!--                       autocomplete="off" autofocus required>-->
            <!--            </div>-->
        </div>
    </div>


    <div class="row" style="margin-top: 40px;margin-bottom: 60px;">
        <div class="col-10 col-lg-4">
            <button id="createReport"
                    class="btn btn-block btn-outline-primary h-100" style="margin-left: 30px;">Построить отчет
            </button>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-12">
        <div class="card card-tasknew">
            <div class="card-body-reports">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <canvas id="myDoughnutChart" style="height: 170px; width: 170px;margin-bottom: 10%;"></canvas>
                    </div>
                    <div class="col-lg-9 col-12">
                        <h5 style="color: #28416b;">
                            Статистика по задачам
                        </h5>
                        <div class="row">
                            <div class="col-lg-8 col-12 left-report">
                                <div class="row" style="padding-top: 20px;">
                                    <div class="col-6 col-lg-5">
                                        <div>
                                            <div class="text-primary text-statistic">118</div>
                                            <span class="text-reports">В работе</span>
                                        </div>
                                        <div>
                                            <div class="text-success text-statistic">90</div>
                                            <span
                                                    class="text-reports">Выполнено</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-5">
                                        <div>
                                            <div class="text-danger text-statistic">12</div>
                                            <span
                                                    class="text-reports">Просрочено</span>
                                        </div>
                                        <div>
                                            <div class="text-warning text-statistic">15</div>
                                            <span
                                                    class="text-reports">Перенесено</span>
                                        </div>
                                        <div>
                                            <div class="text-dark text-statistic">12</div>
                                            <span class="text-reports">Отменено</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 right-report">
                                <h2 class="mb-0 count-tasks-reports">340</h2>
                                <div class="count-info-reports">
                                    <span class="count-info-reports-content"
                                          style="font-size: 70%;">Всего задач за выбранный период</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 60px;">
    <div class="col-12">
        <label class="label-tasknew">
            Статистика по сотрудникам за выбранный период
        </label>
        <div style="padding: 0.8rem;" class="d-sm task-box">
            <div style="padding-left: 7px;">
                <div class="row sort small text-reports">
                    <div class="col-4 text-center">
                        <span>Сотрудник <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Выполнил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Поручил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Просрочил <i class="fas fa-sort d-none"></i></span>
                    </div>
                    <div class="col-2 text-center">
                        <span>Перенес <i class="fas fa-sort d-none"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require_once __ROOT__ . '/engine/backend/other/company.php';
        foreach ($sql

                 as $n):
            $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
            $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 col-lg-1">
                                    <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added m-0">
                                </div>
                                <div class="col-10 col-lg-3 text-left pl-0 pr-0">
                                    <span class="mb-1 text-color-new"><?= $n["name"] ?> <?= $n["surname"] ?></span>
                                </div>
                                <div class="col-3 col-lg-2 text-center">
                                    <span class="text-color-new"><?= $n['doneAsWorker'] ?></span>
                                </div>
                                <div class="col-3 col-lg-2 text-center">
                                    <span class="text-color-new"><?= $n['doneAsManager'] ?></span>
                                </div>
                                <div class="col-3 col-lg-2 text-center">
                                    <span class="text-color-new"><?= $overdue ?></span>
                                </div>
                                <div class="col-3 col-lg-2 text-center">
                                    <span class="text-color-new">15</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
        ?>
    </div>
</div>
<?php endif; ?>


<div>
    <div class="row tasks-reports-container" style="margin-top: 60px; display: none">
        <div class="col-12">
            <label class="label-tasknew">
                Задачи за выбранный период
            </label>
        </div>
    </div>

    <div class="row" style="margin-top: 40px;">
        <div class="col-12">
            <div class="tasks-list-report-empty">
            </div>
            <div class="tasks-list-report">
            </div>
        </div>
    </div>

    <script>
        var ctx = $("#myDoughnutChart");
        var myDoughnutChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [10, 20, 30]
                }],
                labels: [
                    'Red',
                    'Yellow',
                    'Blue'
                ]
            },
            options: {
                responsive: true,
                legend: {
                    display: false,
                    position: "bottom",
                    labels: {
                        fontColor: "#333",
                        fontSize: 16
                    }
                },
                title: {
                    display: true,
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                cutoutPercentage: 92,
            }
        });
    </script>

    <!--<div class="card">-->
    <!--    <div class="card-body text-center">-->
    <!--        <h2 class="d-inline text-uppercase font-weight-bold">-->
    <!--            Demo-->
    <!--        </h2>-->
    <!--        <h5 class="text-center">-->
    <!--            Общая статистика-->
    <!--        </h5>-->
    <!--        <hr>-->
    <!--        <div class="row text-center">-->
    <!--            <div class="col">-->
    <!--                <p class="text-muted">Выполнено</p>-->
    <!--                <p>50</p>-->
    <!--            </div>-->
    <!--            <div class="col">-->
    <!--                <p class="text-muted">Просрочено</p>-->
    <!--                <p>12</p>-->
    <!--            </div>-->
    <!--            <div class="col">-->
    <!--                <p class="text-muted">Комментарии</p>-->
    <!--                <p>123</p>-->
    <!--            </div>-->
    <!--            <div class="col">-->
    <!--                <p class="text-muted">События</p>-->
    <!--                <p>300</p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!---->
    <!--<form id="create-report" method="post">-->
    <!--    <div class="card mt-3">-->
    <!--        <div class="card-body">-->
    <!--            <h5>-->
    <!--                Построение отчета-->
    <!--            </h5>-->
    <!--            <div class="row">-->
    <!--                <div class="col">-->
    <!--                    <label>-->
    <!--                        Дата начала:-->
    <!--                    </label>-->
    <!--                    <input type="date" class="form-control form-control-sm" value="-->
    <? // //= $GLOBALS["now"] ?><!--"-->
    <!--                           id="startReportDate">-->
    <!--                </div>-->
    <!--                <div class="col">-->
    <!--                    <label>-->
    <!--                        Дата конца:-->
    <!--                    </label>-->
    <!--                    <input type="date" class="form-control form-control-sm" value="-->
    <? // //= $GLOBALS["now"] ?><!--"-->
    <!--                           id="endReportDate">-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="row mt-2">-->
    <!--                <div class="col-12 col-lg-6">-->
    <!--                    <label>-->
    <!--                        Выберите сотрудника-->
    <!--                    </label>-->
    <!--                    <select class="custom-select custom-select-sm" id="workerReport">-->
    <!--                        --><?php
    //                        require_once __ROOT__ . '/engine/backend/other/company.php';
    //                        foreach ($sql as $n):?>
    <!--                            <option value="--><? // // //= $n['id'] ?><!--"><span>-->
    <? // // //= $n["name"] ?><!-- --><? // // //= $n["surname"] ?><!--</option>-->
    <!--                            --><?php
    //                            ?>
    <!--                        --><?php //endforeach; ?>
    <!--                    </select>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="row mt-3">-->
    <!--                <div class="col text-center">-->
    <!--                    <button class="btn btn-primary create-report">Построить отчет</button>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</form>-->
    <!---->
    <!--<div class="card mt-3 total-report">-->
    <!--    <div class="card-body">-->
    <!--        <table id="dtOrderExample" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">-->
    <!--            <thead>-->
    <!--            <tr class="text-center text-muted">-->
    <!--                <th class="th-sm">ФИО</th>-->
    <!--                <th class="th-sm">В работе</th>-->
    <!--                <th class="th-sm">Просрочено</th>-->
    <!--                <th class="th-sm">Выполнено</th>-->
    <!--            </tr>-->
    <!--            </thead>-->
    <!--            <tbody>-->
    <!---->
    <!--            --><?php
    //            require_once __ROOT__ . '/engine/backend/other/company.php';
    //            foreach ($sql
    //
    //            as $n):
    //            $overdue = DBOnce('COUNT(*) as count', 'tasks', '(worker=' . $n['id'] . ' or manager=' . $n['id'] . ') and status="overdue"');
    //            $inwork = DBOnce('COUNT(*) as count', 'tasks', '(status="new" or status="inwork" or status="returned") and (worker=' . $n['id'] . ' or manager=' . $n['id'] . ')'); ?>
    <!--            <tr>-->
    <!--                <td><span>--><? // //= $n["name"] ?><!-- --><? // //= $n["surname"] ?><!--</td>-->
    <!--                <td class="text-center">--><? // //= $inwork ?><!--</td>-->
    <!--                <td class="text-center">--><? // //= $overdue ?><!--</td>-->
    <!--                <td class="text-center">-->
    <!--                    <span class="badge badge-primary">--><? // //= $n['doneAsManager'] ?><!--</span>-->
    <!--                    <span class="badge badge-dark">--><? // //= $n['doneAsWorker'] ?><!--</span>-->
    <!--                </td>-->
    <!--                --><?php
    //                endforeach;
    //                ?>
    <!--            </tr>-->
    <!--            </tbody>-->
    <!--        </table>-->
    <!--    </div>-->
    <!--</div>-->

    <!--<div class="card mt-3 report-container" style="display: none">-->
    <!--    <div class="card-body">-->
    <!--        <div class="row text-muted text-center">-->
    <!--            <div class="col-8">-->
    <!--                <span class="small">Выполнено</span>-->
    <!--            </div>-->
    <!--            <div class="col-4">-->
    <!--                <span class="small">Просрочено</span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row text-center">-->
    <!--            <div class="col">-->
    <!--                <span class="badge badge-primary done-outcome" data-toggle="tooltip" data-placement="bottom"-->
    <!--                      title="--><? // //= $GLOBALS['_outbox'] ?><!--"></span>-->
    <!--            </div>-->
    <!--            <div class="col">-->
    <!--                <span class="badge badge-dark done-income" data-toggle="tooltip" data-placement="bottom"-->
    <!--                      title="--><? // //= $GLOBALS['_inbox'] ?><!--">></span>-->
    <!--            </div>-->
    <!--            <div class="col">-->
    <!--                <span class="overdue"></span>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="row mt-3">-->
    <!--            <div class="col">-->
    <!--                <div class="text-muted mb-2">Задачи за выбранный период:</div>-->
    <!--                <div class="tasks-list-report-empty">-->
    <!--                </div>-->
    <!--                <div class="tasks-list-report">-->
    <!---->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->

    <script>
        $(document).ready(function () {
            // $('#dtOrderExample').DataTable({
            //     "order": [[3, "desc"]]
            // });
            // $('.dataTables_length').addClass('bs-select');

            $(".input-reports").on('change', function () {
                $(this).css('color', '#353b41');
            });


            $('#typeOfReport').on('change', function () {
                var val = $(this).val();
                if (val == 1) {
                    $('#workerBlockReports').hide()

                } else {
                    $('#workerBlockReports').show()
                }
            });

            $('#createReport').on('click', function (e) {
                e.preventDefault();
                var startDate = $('#startReportDate').val();
                var endDate = $('#endReportDate').val();
                var workerId = $('#workerReport').val();
                if (workerId == null) {
                    console.log('worker = null')
                }
                console.log(startDate);
                console.log(endDate);
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
                            $('.tasks-reports-container').show();
                            $('.report-container').show();
                            $('.done-income').html(data.doneIncome);
                            $('.done-outcome').html(data.doneOutcome);
                            $('.overdue').html(data.overdue);
                            var icon;
                            var bg;
                            var status;
                            var textColor;

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
                                        icon = "fas fa-check text-white";
                                        bg = 'bg-success';
                                        status = 'Задача завершена';
                                        textColor = 'text-success'
                                    } else {
                                        if (e.status === 'postpone') {
                                            icon = "far fa-calendar-plus text-white";
                                            bg = 'bg-warning';
                                            status = 'Перенос срока';
                                            textColor = 'text-warning'
                                        } else {
                                            if (e.status === 'inwork') {
                                                icon = "fas fa-bolt text-white";
                                                bg = 'bg-primary';
                                                status = 'В работе';
                                                textColor = 'text-primary'
                                            } else {
                                                if (e.status === 'pending') {
                                                    icon = "fas fa-eye text-white";
                                                    bg = 'bg-warning';
                                                    status = 'На рассмотрении';
                                                    textColor = 'text-warning'
                                                }
                                            }
                                        }
                                    }
                                    $('.tasks-list-report').append('<a class="text-decoration-none cust" href=\' /task/' + e.id + '/ \'>\n' +
                                        '    <div class="task-card">\n' +
                                        '       <div class="card mb-2 tasks">\n' +
                                        '           <div class="card-body tasks-list">\n' +
                                        '               <div class=\'d-block ' + e.status + ' \'>\n' +
                                        '                   <div class="row">\n' +
                                        '                   <div class="col-2 col-lg-1 task-report text-center">\n' +
                                        '                       <div class=\'reportIcon ' + bg + ' \'>\n' +
                                        '                       <i class=\'' + icon + ' \'>\n' +
                                        '                               </i>\n' +
                                        '                           </div>\n' +
                                        '                           </div>\n' +
                                        '                       <div class="col-6 col-lg-8">\n' +
                                        '                           <div class="text-area-message">\n' +
                                        '                               <span class="taskname report-task-text mb-0"> ' + e.name + ' </span>\n' +
                                        '                           </div>\n' +
                                        '                       </div>\n' +
                                        '                           <div class="col-4 col-lg-3 pl-0 text-center">\n' +
                                        '                               <span class=\'report-task-text ' + textColor + ' \'> ' + status + ' </span>\n' +
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
