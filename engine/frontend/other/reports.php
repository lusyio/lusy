<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinysort/2.3.6/tinysort.min.js"></script>
<?php
global $tariff;
global $tryPremiumLimits;
if ($tariff == 1 || $tryPremiumLimits['report'] < 3): ?>
    <form id="">
        <div class="row">
            <div class="col-12 col-lg-6 top-block-tasknew">
                <label class="label-tasknew">
                    Тип отчета
                </label>
                <div class="mb-2 card card-tasknew">
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports"
                            id="typeOfReport"
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
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports"
                            id="workerReport"
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
                        class="btn btn-block btn-outline-primary h-100" style="margin-left: 30px;" data-toggle="<?= ($tryPremiumLimits['report'] < 3)? 'tooltip': ''?>" data-placement="bottom" title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['report'] ?>/3">Построить отчет
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
                            <canvas id="myDoughnutChart"
                                    style="height: 170px; width: 170px;margin-bottom: 10%;"></canvas>
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
    <?php
else:
    ?>
    <form id="">
        <div class="row">
            <div class="col-12 col-lg-6 top-block-tasknew">
                <label class="label-tasknew">
                    Тип отчета
                </label>
                <div class="mb-2 card card-tasknew">
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports"
                            id="typeOfReport"
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
                    <select class="input-reports custom-select custom-select-sm border-0 card-body-reports"
                            id="workerReport"
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
                <button id="createReportDisabled"
                        class="btn btn-block btn-outline-primary h-100" style="margin-left: 30px;">Построить отчет
                </button>
            </div>
        </div>
    </form>
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
</div>

<div class="modal fade" id="disabledReportsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Дополнительные функции</h5>
            </div>
            <div class="modal-body text-center">
                Извините, Дополнительные функции доступны только в Premium версии.
            </div>
            <div class="modal-footer border-0">
                <?php if ($isCeo): ?>
                    <a href="/payment/" class="btn btn-primary">Перейти к тарифам</a>
                <?php endif; ?>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<script>
    var ctx = $("#myDoughnutChart");
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [118, 90, 12, 15, 12],
                backgroundColor: [
                    'rgb(93,149,219)',
                    'rgb(153,196,107)',
                    'rgb(210,79,94)',
                    'rgb(237,196,93)',
                    'rgb(113,117,121)'
                ],
            }],
            labels: [
                'В работе',
                'Выполнено',
                'Просрочено',
                'Перенесено',
                'Отменено'
            ],

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

<script>
    $(document).ready(function () {

        $('#createReportDisabled').on('click', function (e) {
            e.preventDefault();
            $('#disabledReportsModal').modal('show');
        });

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
