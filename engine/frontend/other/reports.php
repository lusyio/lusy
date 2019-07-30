<script src="https://www.chartjs.org/dist/2.8.0/Chart.min.js"></script>
<script src="https://www.chartjs.org/samples/latest/utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinysort/2.3.6/tinysort.min.js"></script>

<form>
    <div class="row">
        <div class="col-12 col-lg-6 top-block-tasknew">
            <label class="label-tasknew">
                Тип отчета
            </label>
            <div class="card card-tasknew" id="selectTypeReport">
                <div class="report-select">
                    <div class="responsible-card">
                        <div val="1" class="select-report">
                            <div class="row">
                                <div class="col text-left pr-0">
                                    <span class="mb-1 add-coworker-text">Отчет по компании</span>
                                </div>
                                <div class="col-2 pl-0">
                                    <i class="fas fa-exchange-alt icon-change-responsible"></i>
                                </div>
                            </div>
                        </div>
                        <div val="2" class="select-report">
                            <div class="row">
                                <div class="col text-left pr-0">
                                    <span class="mb-1 add-coworker-text">Отчет по сотруднику</span>
                                </div>
                                <div class="col-2 pl-0">
                                    <i class="fas fa-exchange-alt icon-change-responsible"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container container-report border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew"
                     style="max-height: 50px;">
                    <div class="placeholder-report"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                    <div val="1" class="add-report d-none">
                        <span class="card-coworker">Отчет по компании</span>
                    </div>
                    <div val="2" class="add-report d-none">
                        <span class="card-coworker">Отчет по сотруднику</span>
                    </div>
                    <div class="position-absolute icon-newtask icon-change-report">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 top-block-tasknew">
            <label class="label-tasknew">
                Дата начала
            </label>
            <div class="mb-2 card card-tasknew">
                <input type="date" class="input-reports form-control border-0 card-body-reports"
                       style="height: 50px; font-size: 14px; padding-right: 20px !important;"
                       id="startReportDate"
                       value="<?= date('Y-m-01') ?>" required>
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
            <?php
            include __ROOT__ . '/engine/frontend/members/responsible.php';
            ?>
            <div class="card card-tasknew" id="selectWorkerReports">
                <div class="container container-responsible border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew"
                     style="max-height: 50px;padding-top: 10px !important;">
                    <div class="placeholder-responsible"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                    <?php
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>" class="add-responsible d-none">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="card-coworker"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                        </div>
                    <?php } ?>
                    <div class="position-absolute icon-newtask icon-newtask-change-responsible">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if ($tariff == 1 || $tryPremiumLimits['report'] < 3): ?>
        <div class="row" style="margin-top: 40px;margin-bottom: 60px;">
            <div class="col-12 position-relative">
                <div class="report-btn position-relative">
                    <div style="z-index: 2;position: relative;">
                        <div class="create-report-btn">
                        <?php if ($tariff == 0): ?>
                        <button id="createReport"
                                class="btn btn-block btn-outline-primary h-100"
                                data-toggle="<?= ($tryPremiumLimits['report'] < 3) ? 'tooltip' : '' ?>"
                                data-placement="bottom"
                                title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['report'] ?>/3">
                            Построить отчет
                        </button>
                        <?php else: ?>
                        <button id="createReport"
                                class="btn btn-block btn-outline-primary h-100">
                            Построить отчет
                        </button>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    <?php
    else:
        ?>
        <div class="row" style="margin-top: 40px;margin-bottom: 60px;">
            <div class="col-12 position-relative">
                <div class="report-btn position-relative">
                    <div style="z-index: 2;position: relative;">
                        <div class="create-report-btn">
                            <button id="createReportDisabled"
                                    class="btn btn-block btn-outline-primary h-100"
                                    data-toggle="<?= ($tryPremiumLimits['report'] < 3) ? 'tooltip' : '' ?>"
                                    data-placement="bottom"
                                    title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['report'] ?>/3">
                                Построить отчет
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</form>

<div id="reportsContainer">

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
    $(document).ready(function () {
        
        $('#createReport').on('mouseleave', function () {
            $(this).tooltip('hide');
        });

        $(".container-report").on('click', function () {
            $(".report-select").fadeToggle(200);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest(".container-report").length) {
                $('.report-select').fadeOut(300);
            }
        });

        $(".select-report").on('click', function () {
            $('.placeholder-report').hide();
            var id = $(this).attr('val');
            var selected = $('.add-report:visible').attr('val');
            $('.responsible-card').find("[val = " + selected + "]").removeClass('d-none');
            $(this).addClass('d-none');
            $('.add-report').addClass('d-none');
            $('.coworker-card').find("[val = " + id + "]").addClass('d-none');
            $('.container-report').find("[val = " + id + "]").removeClass('d-none');
        });

        //
        $(".container-responsible").on('click', function () {
            $(".responsible").fadeToggle(200);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest(".container-responsible").length) {
                $('.responsible').fadeOut(300);
            }
        });

        $(".select-responsible").on('click', function () {
            $('.placeholder-responsible').hide();
            var id = $(this).attr('val');
            var selected = $('.add-responsible:visible').attr('val');
            $('.responsible-card').find("[val = " + selected + "]").removeClass('d-none');
            $(this).addClass('d-none');
            $('.add-responsible').addClass('d-none');
            $('.coworker-card').find("[val = " + id + "]").addClass('d-none');
            $('.container-responsible').find("[val = " + id + "]").removeClass('d-none');
        });

        $('#createReportDisabled').on('click', function (e) {
            e.preventDefault();
            $('#disabledReportsModal').modal('show');
        });

        $(".input-reports").on('change', function () {
            $(this).css('color', '#353b41');
        });

        function slowScroll(id) {
            var offset = 0;
            $('html, body').animate({
                scrollTop: $(id).offset().top - offset
            }, 1000);
            return false;
        }
<?php if ($tariff == 1): ?>
        $.ajax({
            url: '/ajax.php',
            type: 'POST',

            data: {
                ajax: 'total-report',
                // module: 'thisTimeReport',
            },
            success: function (data) {
                $('#reportsContainer').html(data);
                // console.log(data);
            }
        });
<?php endif; ?>
        $('#createReport').on('click', function (e) {
            e.preventDefault();
            var val = $('.add-report:visible').attr('val');
            if (val == undefined) {
                $('#selectTypeReport').css({
                    'background-color': 'rgba(255, 242, 242, 1)',
                    'transition': '1000ms'
                });
                setTimeout(function () {
                    $('#selectTypeReport').css('background-color', '#fff');
                }, 1000)
            }
        });

        $('.select-report').on('click', function (e) {
            e.preventDefault();
            var val = $('.add-report:visible').attr('val');
            console.log(val);
            if (val == 1) {
                $('#workerBlockReports').hide();

                $('#createReport').on('click', function (e) {
                    e.preventDefault();
                    var startDate = $('#startReportDate').val();
                    var endDate = $('#endReportDate').val();
                    var fd = new FormData();
                    fd.append('ajax', 'total-report');
                    // fd.append('module', 'totalReport');
                    fd.append('startDate', startDate);
                    fd.append('endDate', endDate);
                    $.ajax({
                        url: '/ajax.php',
                        type: 'POST',
                        dataType: 'html',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: fd,
                        success: function (data) {
                            $('#reportsContainer').html(data);
                            // console.log(data);
                        }
                    })
                });
            }
            if (val == 2) {
                $('#workerBlockReports').show();

                $('#createReport').on('click', function (e) {
                    e.preventDefault();
                    var startDate = $('#startReportDate').val();
                    var endDate = $('#endReportDate').val();
                    var workerId = $('.add-responsible:visible').attr('val');
                    console.log(workerId);

                    if (workerId != undefined) {
                        var fd = new FormData();
                        fd.append('ajax', 'personal-report');
                        fd.append('workerId', workerId);
                        fd.append('startDate', startDate);
                        fd.append('endDate', endDate);
                        $.ajax({
                            url: '/ajax.php',
                            type: 'POST',
                            dataType: 'html',
                            cache: false,
                            processData: false,
                            contentType: false,
                            data: fd,
                            success: function (data) {
                                $('#reportsContainer').html(data);
                                slowScroll(tasksReport);
                                // console.log(data);
                            }
                        });
                    } else {
                        console.log('error');
                        $('#selectWorkerReports').css({
                            'background-color': 'rgba(255, 242, 242, 1)',
                            'transition': '1000ms'
                        });
                        setTimeout(function () {
                            $('#selectWorkerReports').css('background-color', '#fff');
                        }, 1000)
                    }

                });
            }
        });
    });
</script>
