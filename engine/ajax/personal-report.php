<?php

global $idc;
global $pdo;
global $tariff;
$tryPremiumLimits = getFreePremiumLimits($idc);
if ($tariff == 0) {
    if ($tryPremiumLimits['report'] < 3) {
        updateFreePremiumLimits($idc, 'report');
    } else {
        exit;
    }
}

if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $startDate = filter_var($_POST['startDate'], FILTER_SANITIZE_STRING);
    $endDate = filter_var($_POST['endDate'], FILTER_SANITIZE_STRING);
    $firstDay = strtotime($startDate);
    $lastDay = strtotime('+1 day', strtotime($endDate)) - 1;
} else {
    $firstDay = strtotime('first day of this month midnight');
    $lastDay = strtotime('+1 day midnight') - 1;
}
if (!isset($_POST['workerId'])) {
    exit;
}
$userId = filter_var($_POST['workerId'], FILTER_SANITIZE_NUMBER_INT);

$countOverdueByUserQuery = $pdo->prepare("SELECT COUNT(DISTINCT e.task_id, e.datetime) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE (e.action = 'overdue' AND e.datetime > :firstDay AND e.datetime < :lastDay ) AND e.recipient_id = :userId AND t.worker = :userId");
$countOverdueByUserQuery->execute([':userId' => $userId, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$overdue = $countOverdueByUserQuery->fetch(PDO::FETCH_COLUMN);

$countChangeDateByUserQuery = $pdo->prepare("SELECT COUNT(*) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE ((e.action = 'senddate' AND e.author_id = 1) OR (e.action = 'confirmdate' AND e.author_id > 1)) AND e.datetime > :firstDay AND e.datetime < :lastDay AND e.recipient_id = :userId AND (t.manager = :userId OR t.worker = :userId)");
$countChangeDateByUserQuery->execute([':userId' => $userId, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$changedDate = $countChangeDateByUserQuery->fetch(PDO::FETCH_COLUMN);

$taskDoneManagerQuery = $pdo->prepare("SELECT COUNT(distinct t.id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE t.manager = :userId AND e.action='workdone' AND datetime > :firstDay AND e.datetime < :lastDay");
$taskDoneManagerQuery->execute([':userId' => $userId, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$doneAsManager = $taskDoneManagerQuery->fetch(PDO::FETCH_COLUMN);

$taskDoneWorkerQuery = $pdo->prepare("SELECT COUNT(distinct t.id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE t.worker = :userId AND e.action='workdone' AND datetime > :firstDay AND e.datetime < :lastDay");
$taskDoneWorkerQuery->execute([':userId' => $userId, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$doneAsWorker = $taskDoneWorkerQuery->fetch(PDO::FETCH_COLUMN);

$inworkTasksQuery = $pdo->prepare("SELECT DISTINCT td.taskId, td.taskStatus, td.taskName, td.workerName, td.workerSurname, td.workerEmail, td.workerId FROM (SELECT t.id AS taskId, t.name AS taskName, t.worker AS workerId, u.name AS workerName, u.surname AS workerSurname, u.email AS workerEmail, t.status AS taskStatus, t.idcompany AS companyId, t.datecreate AS taskStartDate, e.datetime AS taskEndDate FROM tasks t LEFT JOIN users u ON t.worker = u.id LEFT JOIN events e ON e.task_id = t.id WHERE e.action IN ('workdone', 'canceltask')) td WHERE td.taskStartDate < :lastDay AND (td.taskEndDate > :firstDay OR td.taskEndDate IS NULL) AND td.workerId = :userId");
$inworkTasksQuery->execute([':userId' => $userId, ':firstDay' => $firstDay, ':lastDay' => $lastDay]);
$inworkTasks = $inworkTasksQuery->fetchAll(PDO::FETCH_ASSOC);

$userFullNameQuery = $pdo->prepare('SELECT name, surname, email FROM users u WHERE id = :userId');
$userFullNameQuery->execute([':userId' => $userId]);
$userFullNameResult = $userFullNameQuery->fetch(PDO::FETCH_ASSOC);

if (trim($userFullNameResult['name'] . ' ' . $userFullNameResult['surname']) == '') {
    $userFullName = $userFullNameResult['email'];

} else {
    $userFullName = trim($userFullNameResult['name'] . ' ' . $userFullNameResult['surname']);
}

$tasks = DB('*','tasks','id!=0 limit 20');

$taskStatusText = [
    'new' => $GLOBALS['_tasknewmanager'],
    'inwork' => $GLOBALS['_inprogresslist'],
    'overdue' => $GLOBALS['_overduelist'],
    'postpone' => $GLOBALS['_postponelist'],
    'pending' => $GLOBALS['_pendinglist'],
    'returned' => $GLOBALS['_returnedlist'],
    'done' => $GLOBALS['_donelist'],
    'canceled' => $GLOBALS['_canceledlist'],
    'planned' => $GLOBALS['_plannedlist'],

];


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
$textColor = [
    'new' => 'text-primary',
    'inwork' => 'text-primary',
    'overdue' => 'text-danger',
    'postpone' => 'text-warning',
    'pending' => 'text-warning',
    'returned' => 'text-primary',
    'done' => 'text-success',
    'canceled' => 'text-secondary',
    'planned' => 'text-info',
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

<div class="row" style="margin-top: 60px;">
    <div class="col-12">
        <label class="label-tasknew" id="scrollAnchor">
            Статистика по сотруднику за выбранный период
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
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 col-lg-1">
                                    <img src="/<?= getAvatarLink($userId) ?>" class="avatar-added m-0">
                                </div>
                                <div class="col-9 col-lg-3 text-left pl-0 pr-0 worker-name-reports text-area-message">
                                    <span class="mb-1 text-color-new"><?= $userFullName ?></span>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks"><?= $doneAsWorker ?></div>
                                    <small class="text-muted company-tasks">Выполнил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new done-tasks-manager"><?= $doneAsManager ?></div>
                                    <small class="text-muted company-tasks">Поручил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new overdue-tasks"><?= $overdue ?></div>
                                    <small class="text-muted company-tasks">Просрочил</small>
                                </div>
                                <div class="col-3 col-lg-2 p-0 text-center">
                                    <div class="text-color-new postpone-tasks"><?= $changedDate ?></div>
                                    <small class="text-muted company-tasks">Перенес</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<div class="row tasks-reports-container" style="margin-top: 60px;">
    <div class="col-12">
        <label class="label-tasknew">
            Задачи сотрудника за выбранный период
        </label>
    </div>
</div>

<div class="row" style="margin-top: 40px;">
    <div class="col-12">
        <div class="tasks-list-report-empty">
        </div>
        <div class="tasks-list-report" id="tasksReport">
            <?php foreach ($inworkTasks as $task) : ?>
                <?php $workerFullName = (trim($task['workerName'] . ' ' . $task['workerSurname']) == '') ? $task['workerEmail'] : trim($task['workerName'] . ' ' . $task['workerSurname']); ?>
                <a class="text-decoration-none cust" href='/task/<?= $task['taskId'] ?>/'>
                    <div class="task-card">
                        <div class="card mb-2 tasks">
                            <div class="card-body tasks-list">
                                <div class='d-block'>
                                    <div class="row">
                                        <div class="col-2 col-lg-1 task-report text-center">
                                            <div class="<?= $bgColor[$task['taskStatus']] ?> reportIcon text-white">
                                                <i class="<?= $iconTask[$task['taskStatus']] ?>"></i>
                                            </div>
                                        </div>
                                        <div class="col-6 col-lg-8">
                                            <div class="text-area-message">
                                                <span class="taskname mb-0"><?= $task['taskName'] ?></span>
                                            </div>
                                            <span class="small mb-0"><?= $workerFullName ?></span>
                                        </div>
                                        <div class="col-4 col-lg-3 pl-0 text-center">
                                    <span class='<?= $textColor[$task['taskStatus']] ?> report-task-text'>
                                        <?= $taskStatusText[$task['taskStatus']] ?>
                                    </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>
