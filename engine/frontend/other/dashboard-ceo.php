<?php
$bgColor = [
    'new' => 'bg-primary',
    'inwork' => 'bg-primary',
    'overdue' => 'bg-danger',
    'postpone' => 'bg-warning',
    'pending' => 'bg-warning',
    'returned' => 'bg-primary',
    'done' => 'bg-success',
    'canceled' => 'bg-secondary',
    'planned' => 'bg-info',
];
$borderColor = [
    'new' => 'border-primary',
    'inwork' => 'border-primary',
    'overdue' => 'border-danger',
    'postpone' => 'border-warning',
    'pending' => 'border-warning',
    'returned' => 'border-primary',
    'done' => 'border-success',
    'canceled' => 'border-secondary',
    'planned' => 'border-info',
];
$iconTask = [
    'new' => 'fas fa-plus',
    'inwork' => 'fas fa-bolt',
    'overdue' => 'fab fa-gripfire',
    'postpone' => 'far fa-calendar-alt',
    'pending' => 'fas fa-eye',
    'returned' => 'fas fa-exchange-alt',
    'done' => 'fas fa-check',
    'canceled' => 'fas fa-times',
    'planned' => 'fas fa-history',
];
$statusColor = [
    'new' => 'text-primary',
    'inwork' => 'text-primary',
    'overdue' => 'text-danger',
    'postpone' => 'text-warning',
    'pending' => 'text-warning',
    'returned' => 'text-primary',
    'done' => 'text-success',
    'canceled' => 'text-danger',
    'planned' => 'text-primary',
];
?>
<link rel="stylesheet" href="/assets/css/swiper.min.css">
<script type="text/javascript" src="/assets/js/Chart.min.js"></script>
<script type="text/javascript" src="/assets/js/utils.js"></script>
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card overflow-hidden chart-card">
            <div class="card-body chart-content">
                <div class="empty-chart display-none">
                    <div class="d-flex">
                        <div>
                            <b>Завершайте задачи</b>
                            <div class="small text-muted">и здесь появится ваш график</div>
                        </div>
                    </div>
                </div>
                <div class="not-empty-chart display-none">
                    <span class="numberSlide">
                        <?= $taskDoneCountCurrentMonth ?>
                    </span>
                    <div>
                        <b>Завершено</b>
                        <div class="small text-muted">в этом месяце</div>
                    </div>
                </div>
            </div>
            <canvas class="d-none" id="canvas"></canvas>
            <div class="chart">
                <?php if (!is_null($taskDoneDelta)): ?>
                    <span class="percent-chart" data-toggle="tooltip" data-placement="bottom"
                          title="Разница за аналогичный период в прошлом месяце">
                    <?= $taskDoneDelta; ?>
                </span>
                <?php else: ?>
                    <span class="percent-chart" data-toggle="tooltip" data-placement="bottom"
                          title="По мере выполнения задач будет доступна разница за периоды">
                    <i class="fas fa-info-circle"></i>
                </span>
                <?php endif; ?>
                <?php if ($taskDoneCountOverall == 0): ?>
                    <span class="percent-chart" data-toggle="tooltip" data-placement="bottom"
                          title="Пока что на графике представлены случайные значения">
                    <i class="fas fa-info-circle"></i>
                </span>
                <?php endif; ?>
            </div>
            <span class="bg-icon-achieve">
                <img src="/assets/svg/trophy2.svg" class="svg-icon bg-chart-trophy"/>
            </span>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div id="taskListSlide" class="position-relative">
            <?php foreach ($tasks as $task): ?>
                <a href="/task/<?= $task['id'] ?>/" class="text-decoration-none cust">
                    <div class="taskDiv mb-2">
                        <div class="row">
                            <div class="col-1 mr-3 text-center">
                                <div>
                                    <span class="text-lowercase font-weight-bold">
                                        <?= date("j.m", $task['datedone']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 col-lg-1 mr-4">
                                <div class="<?= $bgColor[$task['status']] ?> logIcon">
                                    <i class="<?= $iconTask[$task['status']] ?>"></i>
                                </div>
                            </div>
                            <div class="col-7 col-lg-4 col-xlg-5 p-0 pl-2">
                                <p class="mb-0 font-weight-bold text-area-message"> <?= $task['name']; ?></p>
                                <p class="mb-0 text-muted small text-area-message"><?= $task['managerName'] . ' ' . $task['managerSurname'] ?></p>
                            </div>
                            <div class="col pl-0">
                                <div class="statusText font-weight-bold text-right text-">
                                    <span class="statusText-line">
                                        <?= $GLOBALS["_{$task['status']}"] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if ($countAllTasks == 1): ?>
                <a href="/task/new/" class="text-decoration-none cust">
                    <div class="taskDiv create-new-task mb-2">
                        <div class="row">
                            <div class="col text-center">
                                <span class="text-muted">Создать новую задачу</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/task/new/" class="text-decoration-none cust">
                    <div class="taskDiv create-new-task mb-2">
                        <div class="row">
                            <div class="col text-center">
                                <span class="text-muted">Создать новую задачу</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endif; ?>

            <?php if ($countAllTasks == 2): ?>
                <a href="/task/new/" class="text-decoration-none cust">
                    <div class="taskDiv create-new-task mb-2">
                        <div class="row">
                            <div class="col text-center">
                                <span class="text-muted">Создать новую задачу</span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
            <?php if ($countAllTasks == 0): ?>
                <a href="/task/new/" class="text-decoration-none">
                    <div class="taskDiv create-new-task search-empty-new-task">
                        <div class="card-body">
                            <span><?= _('You have no tasks yet, create the first task.') ?></span>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
            <?php if ($countAllTasks >= 4): ?>
                <a href="/tasks/">
                <span class="icon-more-tasks" data-toggle="tooltip" data-placement="bottom" title="Больше задач">
                    <i class="fas fa-sort-down"></i>
                </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if (isset($showGame) && $showGame) : ?>
    <div class="card premiumCard mb-3 mt-3 progressBlock">
        <div class="card-body">
            <div class="row ">
                <?php foreach ($stepContent as $step): ?>
                    <div class="col-4 col-lg-3">
                    <a href="<?= $step['link'] ?>">
                        <div class="d-flex <?=$step['doneStep']?>">
                            <div class="iconDiv">
                                <i class="<?= $step['icon'] ?>"></i>
                            </div>
                            <p class="mb-0 small text-game-step"><?= $step['text'] ?></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
                <div class="col-12 col-lg-3 premiumAcountActivate <?= ($isGameCompleted) ? 'doneStep' : ''?>">
                    <button id="finishSteps" class="btn btn-outline-light">
                        Активировать Premium-аккаунт
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-9">
                    <div class="progress mt-0">
                        <div class="progress-bar" role="progressbar" style="width: <?= $stepProgressBar ?>%" aria-valuenow="<?= $stepProgressBar ?>"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="swiper-container" id="mainSwiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
            <?php
            foreach ($tasks as $task):
                ?>
                <a href="/task/<?= $task['id'] ?>/" class="text-decoration-none cust">
                    <div class="task-card">
                        <div class="card mb-2 tasks">
                            <div class="card-body tasks-list">
                                <div class="d-block border-left-tasks <?= $borderColor[$task['status']] ?>">
                                    <p class="font-weight-light text-ligther d-none"><?= _('Done') ?></p>
                                    <div class="row">
                                        <div class="col-9">
                                            <div>
                                                <span class="taskname"><?= $task['name'] ?></span>
                                            </div>
                                        </div>
                                        <div class="col-3 p-0">
                                            <span class="<?= ($task['status'] == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($task['status'], ['inwork', 'new', 'returned']) && date("Y-m-d", $task['datedone']) == date("Y-m-d")) ? 'text-warning font-weight-bold' : ''; ?>"><?= $task['deadLineDay'] ?> <?= $task['deadLineMonth'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if ($countAllTasks > 20): ?>
                <a href="/tasks/" class="text-decoration-none cust">
                    <div class="task-card">
                        <div class="card mb-2 tasks  pending manager">
                            <div class="card-body tasks-list">
                                <div class="d-block">
                                    <p class="font-weight-light text-ligther d-none"><?= _('Done') ?></p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="text-center">
                                                <span class="taskname"><?= _('Show all tasks') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
            <?php if ($countAllTasks == 0): ?>
                <a href="/task/new/" class="text-decoration-none">
                    <div class="card search-empty">
                        <div class="card-body">
                            <span><?= _('You have no tasks yet, create the first task.') ?></span>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="card position-relative d-none">
    <div class="card-body">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item text-center active">
                    <h1 class="font-weight-bold text-success mb-0">23</h1>
                    <small class="text-dark font-weight-bold"><?= _('Completed tasks') ?></small>
                    <hr>
                    <div class="d-flex justify-content-center">
                        <div class="mb-1 mr-3">
                            <small class="text-secondary"><i
                                        class="fas fa-fire-alt text-danger fa-fw mr-2"></i><?= _('Was overdue') ?> -
                                <span class="font-weight-bold text-dark">9</span></small>
                        </div>
                        <div>
                            <small class="text-secondary"><i
                                        class="fas fa-clock text-warning fa-fw mr-2"></i><?= _('Postpone requested') ?>
                                - <span class="font-weight-bold text-dark">1</span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 mb-3">
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash">
        <a href="/tasks/" class="text-decoration-none">
            <div class="card">
                <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-tasks text-secondary fa-fw mr-1"></i></span>
                    <p class="mb-0"><span class="font-weight-bold"><?= $all ?></span> <span
                                class="text-lowercase"><?= ngettext('task', 'tasks', $all) ?></span></p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash <?= ($inwork == 0) ? "no-events-no" : ''; ?>">
        <div>
            <a href="/tasks/#inwork" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-bolt text-primary fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $inwork ?></span> <span
                                    class="text-lowercase"><?= _('In work') ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash <?= ($overdue == 0) ? "no-events-no" : ''; ?>">
        <div>
            <a href="/tasks/#overdue" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                    <span class="font-weight-bold float-left mr-2"><i
                                class="fas fa-fire-alt text-danger fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $overdue ?></span> <span
                                    class="text-lowercase"><?= _('Overdue') ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash <?= ($pending == 0) ? "no-events-no" : ''; ?>">
        <div>
            <a href="/tasks/#pending" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                        <span class="font-weight-bold float-left mr-2"><i
                                    class="fas fa-search text-success fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $pending ?></span> <span
                                    class="text-lowercase"><?= _('Pending') ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash <?= ($postpone == 0) ? "no-events-no" : ''; ?>">
        <div>
            <a href="/tasks/#postpone" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                        <span class="font-weight-bold float-left mr-2"><i
                                    class="fas fa-clock text-warning fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $postpone ?></span> <span
                                    class="text-lowercase"><?= _('Postponement') ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-sm-4 col-lg-4 col-md-6 mb-3 card-tasks-dash <?= ($postpone == 0) ? "no-events-no" : ''; ?>">
        <div>
            <a href="/tasks/#postpone" class="text-decoration-none">
                <div class="card">
                    <div class="card-body pb-2 pt-2">
                        <span class="font-weight-bold float-left mr-2"><i
                                    class="fas fa-clock text-info fa-fw mr-1"></i></span>
                        <p class="mb-0"><span class="font-weight-bold"><?= $planned ?></span> <span
                                    class="text-lowercase"><?= _('Planned') ?></span></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="mt-1 pb-0">
    <span class="font-weight-bold d-none"><?= _('History') ?></span>
    <div id="logDashBoard">
        <div id="newEventsTitle" class="text-center other-func text-secondary position-relative mt-3 mb-4 <?= (count($newEvents) > 0) ? '' : 'd-none' ?>">
            <div class="additional-func">
                <span>Новые события</i></span>
            </div>
        </div>
        <ul id="newEventsTimeline" class="timeline">
            <?php foreach ($newEvents as $event): ?>
                <?php  renderEvent($event); ?>
            <?php endforeach; ?>
        </ul>
        <div class="row mt-25-tasknew">
            <div class="col-12 position-relative">
                <div class="text-center other-func position-relative text-secondary mb-4">
                    <div id="oldEventsCollapse" class="additional-func">
                        <span>Просмотренные события<i class="fas fa-caret-down ml-3"></i></span>
                    </div>
                </div>
                <div class="collapse" id="oldEvents">
                    <ul class="timeline" id="oldEventTimeline">
                        <?php $eventNumber = 1; ?>
                        <?php foreach ($oldEvents as $event): ?>
                            <?php if ($eventNumber < 21) {
                                renderEvent($event);
                                $eventNumber++;
                            } ?>
                        <?php endforeach; ?>
                        <?php if (count($oldEvents) > 20): ?>
                            <a href="/log/">
                                <div class="load-log-dashboard">
                                    <div id="loadLogDashboard" class="rounded-circle btn btn-light">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </a>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($isFirstLogin): ?>
    <div class="modal fade" id="afterRegModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 text-center d-block">
                    <h4 class="modal-title" id="exampleModalLabel">Добро пожаловать в Lusy.io</h4>
                </div>
                <div class="modal-body text-left">
                    <p class="text-muted-new">
                        Мы создали для вас аккаунт со следующими параметрами:
                    </p>
                    <div class="row mb-3">
                        <div class="col-3">
                            <span class="after-reg-text">Имя компании</span>
                        </div>
                        <div class="col">
                            <label class="cd-username" for="afterRegCompanyname"><i
                                        class="fas fa-user text-muted-new"></i></label>
                            <input id="afterRegCompanyname" class="form-control" type="text" placeholder="Имя компании"
                                   value="<?= $companyName; ?>">
                            <span id="companynameOK" class="icon-after-reg position-absolute text-success display-none">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-3">
                            <span class="after-reg-text">Ваш email</span>
                        </div>
                        <div class="col">
                            <label class="cd-username" for="afterRegEmail"><i
                                        class="fas fa-envelope text-muted-new"></i></label>
                            <input id="afterRegEmail" class="form-control" type="email" placeholder="email"
                                   value="<?= $email; ?>">
                            <span id="emailOK" class="icon-after-reg  position-absolute text-success display-none">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-3">
                            <span class="after-reg-text">Пароль</span>
                        </div>
                        <div class="col position-relative">
                            <label class="cd-username" for="afterRegPassword"><i
                                        class="fas fa-key text-muted-new"></i></label>
                            <input id="afterRegPassword" class="form-control" type="text" placeholder="Пароль"
                                   value="<?= $password; ?>">
                            <input id="currentPassword" type="hidden" value="<?= $password; ?>">
                            <span class="icon-after-reg  info-reg position-absolute text-ligther"
                                  data-toggle="tooltip" data-placement="bottom"
                                  title="Минимальное кол-во символов - 6">
                            <i class="fas fa-info-circle"></i>
                        </span>
                            <span id="passwordOK" class="icon-after-reg  position-absolute text-success display-none">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-8 text-right">
                            <button id="saveAfterReg" class="btn-afterreg btn btn-violet text-white">
                                Сохранить изменения
                            </button>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn-afterreg btn btn-light text-muted-new" data-dismiss="modal">
                                <span class="small">Пропустить</span>
                            </button>
                        </div>
                    </div>
                    <span class="position-absolute bg-after-reg">
                <i class="fas fa-sign-in-alt icon-limit-modal"></i>
                </span>
                </div>
                <div class="modal-footer" style="justify-content: start">
                    <p class="text-muted-new small mb-0">
                        Вы в любой момент можете поменять данные в
                        <a class="btn-link" href="https://s.lusy.io/settings/">настройках</a>
                    </p>
                </div>
                <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
                <div class="text-center position-absolute spinner-after-reg">
                    <div class="spinner-border"
                         role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script src="/assets/js/swiper.min.js"></script>
<script type="text/javascript">


    Chart.defaults.multicolorLine = Chart.defaults.line;
    Chart.controllers.multicolorLine = Chart.controllers.line.extend({
        draw: function (ease) {
            var
                startIndex = 0,
                meta = this.getMeta(),
                points = meta.data || [],
                colors = this.getDataset().colors,
                area = this.chart.chartArea,
                originalDatasets = meta.dataset._children
                    .filter(function (data) {
                        return !isNaN(data._view.y);
                    });

            function _setColor(newColor, meta) {
                meta.dataset._view.borderColor = newColor;
            }

            if (!colors) {
                Chart.controllers.line.prototype.draw.call(this, ease);
                return;
            }

            for (var i = 2; i <= colors.length; i++) {
                if (colors[i - 1] !== colors[i]) {
                    _setColor(colors[i - 1], meta);
                    meta.dataset._children = originalDatasets.slice(startIndex, i);
                    meta.dataset.draw();
                    startIndex = i - 1;
                }
            }

            meta.dataset._children = originalDatasets.slice(startIndex);
            meta.dataset.draw();
            meta.dataset._children = originalDatasets;

            points.forEach(function (point) {
                point.draw(area);
            });
        }
    });


    function createConfig(details, data) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        var height = $('.chart-container').children().height();

        let gradient = ctx.createLinearGradient(0, 0, 0, height);
        gradient.addColorStop(1, 'white');
        gradient.addColorStop(1, 'white');
        ctx.fillStyle = gradient;
        ctx.fillRect(10, 10, 200, 100);
        return {
            type: 'multicolorLine',
            data: {
                labels: [<?= $dataForChartString ?>],
                datasets: [{
                    steppedLine: details.steppedLine,
                    data: data,
                    fill: false,
                    backgroundColor: gradient,
                    borderColor: '#799cfe',
                    colors: ['', '#799cfe', '#799cfe', '#799cfe', '#799cfe', '#799cfe', '#e9ecf3'],
                    borderWidth: 5,
                }]
            },
            options: {
                responsive: true,
                layout: {
                    padding: {
                        top: 10,
                        left: 20,
                        right: 20,
                        bottom: 20
                    }
                },
                title: {
                    display: false,
                    text: details.label,
                },
                label: {
                    display: false
                },
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false,
                        ticks: {
                            suggestedMin: -2
                        }
                    }]
                },
                elements: {
                    point: {
                        radius: 0,
                        hitRadius: 10,
                    }
                }
            }
        };
    }

    var container = document.querySelector('.chart');

    var steppedLineSettings = [{
        color: window.chartColors.red
    }];

    var randomScalingFactor = function () {
        return Math.ceil(Math.random() * 5) * Math.ceil(Math.random() * 2);
    };

    var emptyData = [0, 0, 0, 0, 0, 0, 0];
    if ([<?=$taskCountString?>].length === emptyData.length && [<?=$taskCountString?>].every((value, index) => value === emptyData[index])) {
        data = [
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor(),
            randomScalingFactor()
        ];
        $('.empty-chart').show();
    } else {
        var data = [<?=$taskCountString?>];
        $('.not-empty-chart').css('display', 'flex');
    }

    steppedLineSettings.forEach(function (details) {
        var div = document.createElement('div');
        div.classList.add('chart-container');

        var canvas = document.createElement('canvas');
        div.appendChild(canvas);
        container.appendChild(div);

        var ctx = canvas.getContext('2d');
        var config = createConfig(details, data);
        new Chart(ctx, config);
    });
</script>
<script>
    var swiper = new Swiper('.swiper-container', {
        loop: true,
    });
</script>
<script>
    var pageName = 'dashboard';
    $(document).ready(function () {
        hideGradient();
        $('.percent-chart').show();

        function hideGradient() {
            if ($('.task-card:visible').length < 4) {
                $("#bottomGradient").hide();
            } else {
                $("#bottomGradient").show();
            }
        }

        $('.timeline').on('mouseover', '.new-event', function () {
            $(this).removeClass('new-event');
            var eventId = $(this).data('event-id');
            markAsRead(eventId);
            $(this).on('mouseleave', function () {
                var el = $(this);
                el.css("transform", "translateX(1000px)");
                setTimeout(function () {
                    $('#oldEventTimeline').prepend(el);
                    el.css("transform", "");
                    if ($('.new-event').length === 0) {
                        $('#newEventsTitle').addClass('d-none');
                    }
                }, 500);
                $(this).off('mouseleave')

            });
        });

        $('#oldEventsCollapse').on('click', function () {
            $('#oldEvents').collapse('toggle');
            var arrowIcon = $('#oldEventsCollapse i');
            if (arrowIcon.css("transform") === 'none') {
                arrowIcon.css("transform", "scaleY(-1)");
            } else {
                arrowIcon.css("transform", "");
            }
        });

        $('#finishSteps').on('click', function () {
            if ($('.not-finished').length > 0) {
                var time = 0;
                $('.not-finished').each(function () {
                    var that = $(this);
                    setTimeout(function () {
                        that.css('opacity', '1');
                        setTimeout(function () {
                            that.css('opacity', '');
                        }, 500);
                    }, time);
                    time += 500;
                })
            } else {
                var fd = new FormData();
                fd.append('module', 'gamePromo');
                fd.append('ajax', 'payments');
                    $.ajax({
                        url: '/ajax.php',
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: fd,
                        success: function () {
                            window.location.href = '/payment/';
                        },
                    });
                }
        })
    });
</script>
<?php if ($isFirstLogin): ?>
<script>
    $(document).ready(function () {
        $('#afterRegModal').modal('show');

        var security = 0;
        var securityMail = 0;
        var securityPass = 0;

        var email = $('#afterRegEmail').val();
        var regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
        var checkMail = regMail.exec(email);

        var password = $('#afterRegPassword').val();
        var reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
        var checkPass = reg.exec(password);

        if ($('#afterRegCompanyname').val() != ''){
            $('#afterRegCompanyname').css({
                'border': '1px solid #ccc',
                'color': '#495057'
            });
            $('#companynameOK').show();
        } else {
            $('#companynameOK').hide();
        }

        if (checkMail == null) {
            securityMail = 0;
            $('#emailOK').hide();
        } else {
            securityMail = 1;
            $('#emailOK').show();
        }

        if (checkPass == null) {
            $('.info-reg').show();
            $('#passwordOK').hide();
        } else {
            securityPass = 1;
            $('.info-reg').hide();
            $('#passwordOK').show();
        }

        security = securityMail + securityPass;

        if (security != 2) {
            $('#saveAfterReg').prop('disabled', true);
        } else {
            $('#saveAfterReg').prop('disabled', false);
        }

        $('#afterRegCompanyname').on('change', function () {
           var $this = $(this);

           if ($this.val() != ''){
               $this.css({
                   'border': '1px solid #ccc',
                   'color': '#495057'
               });
               $('#companynameOK').show();
           } else {
               $('#companynameOK').hide();
           }
        });


        $('#afterRegEmail').on('keyup', function () {
            var $this = $(this);

            email = $this.val();
            regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
            checkMail = regMail.exec(email);

            if (checkMail == null) {
                $this.css({
                    'border': '1px solid #fbc2c4',
                    'color': '#8a1f11'
                });
                securityMail = 0;
                $('#emailOK').hide();
            } else {
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
                securityMail = 1;
                $('#emailOK').show();
            }
            security = securityMail + securityPass;
            if (security == 2) {
                $("#saveAfterReg").prop('disabled', false);
            } else {
                $("#saveAfterReg").prop('disabled', true);
            }
        });

        $('#afterRegPassword').on('keyup', function () {
            var $this = $(this);
            password = $this.val();
            reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
            checkPass = reg.exec(password);

            if (checkPass == null) {
                $this.css({
                    'border': '1px solid #fbc2c4',
                    'color': '#8a1f11'
                });
                securityPass = 0;
                $('.info-reg').show();
                $('#passwordOK').hide();
            } else {
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
                securityPass = 1;
                $('.info-reg').hide();
                $('#passwordOK').show();
            }
            security = securityMail + securityPass;
            if (security == 2) {
                $("#saveAfterReg").prop('disabled', false);
            } else {
                $("#saveAfterReg").prop('disabled', true);
            }

        });

        $('#saveAfterReg').on('click', function () {
            var email = $('#afterRegEmail').val();
            var password = $('#afterRegPassword').val();
            var currentPassword = $('#currentPassword').val();
            var companyName = $('#afterRegCompanyname').val();

            var fd = new FormData();
            fd.append('email', email);
            fd.append('newPassword', password);
            fd.append('password', currentPassword);
            fd.append('companyName', companyName);
            fd.append('module', 'initChange');
            fd.append('ajax', 'settings');
            if (companyName != ''){
            $('.spinner-after-reg').show();
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                dataType: 'json',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    if (response.error == '') {
                        location.reload();
                    }
                    if (response.error == 'email'){
                        $('#afterRegEmail').css({
                            'border': '1px solid #fbc2c4',
                            'color': '#8a1f11'
                        });
                        $('#emailOK').hide()
                    }
                    if (response.error == 'password'){
                        $('#afterRegPassword').css({
                            'border': '1px solid #fbc2c4',
                            'color': '#8a1f11'
                        });
                        $('#passwordOK').hide()
                    }
                    if (response.error == 'company'){
                        $('#afterRegCompanyname').css({
                            'border': '1px solid #fbc2c4',
                            'color': '#8a1f11'
                        });
                        $('#companynameOK').hide()
                    }
                },
                complete: function () {
                    $('.spinner-after-reg').hide();
                }
            });
            } else {
                $('#afterRegCompanyname').css({
                    'border': '1px solid #fbc2c4',
                    'color': '#8a1f11'
                });
            }
        });

        $('.carousel').carousel({
            interval: 10000
        });
    });
</script>
<?php endif; ?>
