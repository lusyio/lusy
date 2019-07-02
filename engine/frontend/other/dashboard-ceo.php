<?php

$borderColor = [
    'new' => 'border-primary',
    'inwork' => 'border-primary',
    'overdue' => 'border-danger',
    'postpone' => 'border-warning',
    'pending' => 'border-warning',
    'returned' => 'border-primary',
    'done' => 'border-success',
    'canceled' => 'border-secondary',
];
?>
<link rel="stylesheet" href="/assets/css/swiper.min.css">

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card overflow-hidden chart-card">
            <div class="card-body chart-content">
                <div><span class="numberSlide"><?= $taskDoneCountOverall ?></span><i
                            class="iconSlide fas fa-check float-right"></i>
                </div>
                <div>
                    <small class="text-secondary"><?= _('Tasks done per month') ?></small>
                </div>
            </div>
            <div id="curve_chart"></div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
        <div id="taskListSlide">
            <?php foreach ($tasks as $task): ?>
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
                <div class="card search-empty">
                    <div class="card-body">
                        <span><?= _('You have no tasks yet, create the first task.') ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div class="bottomGradient"></div>
        </div>

    </div>
</div>
<div class="swiper-container" id="mainSwiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
        <!-- Slides -->
        <div class="swiper-slide">
            <?php
            $i = 1;
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
                <?php
                $i++;
                if (($i % 3) == 1) {
                    echo '</div> <div class="swiper-slide">';
                }
                ?>
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

<div class="row mt-3">
    <div class="col-sm-4 mb-3">
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
    <div class="col-sm-4 mb-3">
        <div<?= ($inwork == 0) ? ' class="no-events"' : ''; ?>>
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
    <div class="col-sm-4 mb-3">
        <div<?= ($overdue == 0) ? ' class="no-events"' : ''; ?>>
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
    <div class="col-sm-4 mb-3">
        <div<?= ($pending == 0) ? ' class="no-events"' : ''; ?>>
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
    <div class="col-sm-4 mb-3">
        <div<?= ($postpone == 0) ? ' class="no-events"' : ''; ?>>
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
</div>

<div class="card mt-1">
    <div class="card-body pb-0">
        <span class="font-weight-bold"><?= _('History') ?></span>
        <hr class="mb-0">
        <div id="logDashBoard">
            <ul class="timeline" style="bottom: 0px;">
                <?php foreach ($events as $event): ?>
                    <?php renderEvent($event); ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
<script src="/assets/js/swiper.min.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    $(window).resize(function(){
        drawChart();
    });

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Year', 'Задач'],
            ['2013',  1000],
            ['2014',  1170],
            ['2015',  660],
            ['2016',  1030,]
        ]);

        var options = {
            series: {
                0: {
                    color: '#2DA64D',
                },
            },
            width: '100%',
            height: 121,
            curveType: 'function',
            legend: {
                position: 'none'
            },
            hAxis:{
                viewWindowMode: 'maximized',
                textPosition: 'none',
                gridlines:{
                    color: '#fff',
                },
                baselineColor: '#fff',
            },
            vAxis:{
                textPosition: 'none',
                gridlines:{
                    color: '#fff',
                },
                minValue: 0,
                baselineColor: '#bfe4ca',
            },
            chartArea:{
                width: '100%',
                height: '100%',
            },
        };

        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
    }
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
        });

        function createConfig(details, data) {
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            var height = $('.chart-container').children().height();

            let gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(1, 'rgba(40, 167, 69, 0.1)');
            gradient.addColorStop(1, 'white');
            ctx.fillStyle = gradient;
            ctx.fillRect(10, 10, 200, 100);
            return {
                type: 'line',
                data: {
                    labels: [<?= $datesString ?>],
                    datasets: [{
                        steppedLine: details.steppedLine,
                        data: data,
                        fill: 'start',
                        backgroundColor: gradient,
                        borderColor: '#28a745'
                    }]
                },
                options: {
                    responsive: true,
                    layout: {
                        padding: {
                            top: 10
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
                            radius: 0
                        }
                    }
                }
            };
        }


        window.onload = function () {

            var container = document.querySelector('.chart');

            var data = [<?=$taskCountString?>];

            var steppedLineSettings = [{
                color: window.chartColors.red
            }];


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
        };

    });
</script>
<script>
    $(document).ready(function () {
        $('.carousel').carousel({
            interval: 10000
        });
    });
</script>