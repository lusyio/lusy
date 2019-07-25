<?php
global $idc;
global $pdo;
if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING);
    $endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING);
    $firstDay = strtotime($startDate);
    $lastDay = strtotime('+1 day', strtotime($endDate)) - 1;
} else {
    $firstDay = strtotime('first day of month midnight');
    $lastDay = strtotime('+1 day midnight') - 1;
}
$countInworkTasksQuery = $pdo->prepare("SELECT COUNT(DISTINCT td.taskId) FROM (SELECT t.id AS taskId, t.idcompany AS companyId, t.datecreate AS taskStartDate, e.datetime AS taskEndDate FROM tasks t LEFT JOIN events e ON e.task_id = t.id WHERE e.action IN ('workdone', 'canceltask')) td WHERE td.taskStartDate < :lastDay AND (td.taskEndDate > :firstDay OR td.taskEndDate IS NULL) AND td.companyId = :companyId");
$countInworkTasksQuery->execute([':companyId' => $idc, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$countInworkTasks = $countInworkTasksQuery->fetch(PDO::FETCH_COLUMN);

$countTaskDoneQuery = $pdo->prepare("SELECT COUNT(distinct t.id) AS count FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE e.company_id = :companyId AND e.action='workdone' AND datetime > :firstDay AND datetime < :lastDay");
$countTaskDoneQuery->execute([':companyId' => $idc, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$countTaskDone = $countTaskDoneQuery->fetch(PDO::FETCH_COLUMN);

$countOverdueQuery = $pdo->prepare("SELECT COUNT(DISTINCT e.task_id, e.datetime) FROM events e WHERE (e.action = 'overdue' AND e.datetime > :firstDay AND e.datetime < :lastDay ) AND e.company_id = :companyId");
$countOverdueQuery->execute([':companyId' => $idc, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$countOverdue = $countOverdueQuery->fetch(PDO::FETCH_COLUMN);

$countChangeDateQuery = $pdo->prepare("SELECT COUNT(*) FROM events e WHERE e.action IN ('senddate', 'confirmdate') AND e.datetime > :firstDay AND e.datetime < :lastDay AND author_id = 1 AND e.company_id = :companyId");
$countChangeDateQuery->execute([':companyId' => $idc, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$countChangeDate = $countChangeDateQuery->fetch(PDO::FETCH_COLUMN);

$countCancelQuery = $pdo->prepare("SELECT COUNT(*) FROM events e WHERE e.action = 'canceltask' AND e.datetime > :firstDay AND e.datetime < :lastDay AND author_id = 1 AND e.company_id = :companyId");
$countCancelQuery->execute([':companyId' => $idc, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$countCancel = $countCancelQuery->fetch(PDO::FETCH_COLUMN);
?>

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
                                            <div class="text-primary text-statistic"><?= ($countInworkTasks) ? $countInworkTasks : 0 ?></div>
                                            <span class="text-reports">В работе</span>
                                        </div>
                                        <div>
                                            <div class="text-success text-statistic"><?= ($countTaskDone) ? $countTaskDone : 0 ?></div>
                                            <span
                                                class="text-reports">Выполнено</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-lg-5">
                                        <div>
                                            <div class="text-danger text-statistic"><?= ($countOverdue) ? $countOverdue : 0 ?></div>
                                            <span
                                                class="text-reports">Просрочено</span>
                                        </div>
                                        <div>
                                            <div class="text-warning text-statistic"><?= ($countChangeDate) ? $countChangeDate : 0 ?></div>
                                            <span
                                                class="text-reports">Перенесено</span>
                                        </div>
                                        <div>
                                            <div class="text-dark text-statistic"><?= ($countCancel) ? $countCancel : 0 ?></div>
                                            <span class="text-reports">Отменено</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 right-report">
                                <h2 class="mb-0 count-tasks-reports"><?= ($countInworkTasks) ? $countInworkTasks : 0 ?></h2>
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
        <label class="label-tasknew" id="scrollAnchor">
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
                                <div class="col-3 col-lg-1">
                                    <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added m-0">
                                </div>
                                <div class="col-9 col-lg-3 text-left pl-0 pr-0 worker-name-reports text-area-message">
                                    <span class="mb-1 text-color-new"><?= $n["name"] ?> <?= $n["surname"] ?></span>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks"><?= $n['doneAsWorker'] ?></div>
                                    <small class="text-muted company-tasks">Выполнил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks-manager"><?= $n['doneAsManager'] ?></div>
                                    <small class="text-muted company-tasks">Поручил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new overdue-tasks"><?= $overdue ?></div>
                                    <small class="text-muted company-tasks">Просрочил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new postpone-tasks">15</div>
                                    <small class="text-muted company-tasks">Перенес</small>
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
            <a class="text-decoration-none cust" href='#'
            <div class="task-card">
                <div class="card mb-2 tasks">
                    <div class="card-body tasks-list">
                        <div class='d-block'>
                            <div class="row">
                                <div class="col-2 col-lg-1 task-report text-center">
                                    <div class='reportIcon'>
                                        <i class=''></i>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-8">
                                    <div class="text-area-message">
                                        <span class="taskname mb-0"> Название задачи </span>
                                    </div>
                                    <span class="small mb-0"> workerFullName  </span>
                                </div>
                                <div class="col-4 col-lg-3 pl-0 text-center">
                                    <span class='report-task-text'> статус </span>
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

<script>
    var ctx = $("#myDoughnutChart");
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    <?= ($countInworkTasks) ? $countInworkTasks : 0 ?>,
                    <?= ($countTaskDone) ? $countTaskDone : 0 ?>,
                    <?= ($countOverdue) ? $countOverdue : 0 ?>,
                    <?= ($countChangeDate) ? $countChangeDate : 0 ?>,
                    <?= ($countCancel) ? $countCancel : 0 ?>
                ],
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