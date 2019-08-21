<?php
global $id;
global $idc;
global $roleu;
global $_overdue;
global $_pending;
global $_inprogress;
global $_history;
global $_pending;
global $_alltasks;
global $_postpone;
global $pdo;
global $cometHash;
global $cometTrackChannelName;
global $supportCometHash;
global $nameu;

$countStatusQuery = $pdo->prepare("SELECT COUNT(DISTINCT t.id) AS count, t.status FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE (worker= :userId OR manager= :userId OR tc.worker_id = :userId) and t.status IN ('new', 'inwork', 'returned', 'pending', 'postpone', 'overdue') GROUP BY t.status");
$countStatusQuery->execute([':userId' => $id]);
$countStatus = $countStatusQuery->fetchAll(PDO::FETCH_ASSOC);
$inwork = 0;
$pending = 0;
$postpone = 0;
$overdue = 0;
$all = 0;
foreach ($countStatus as $group) {
    if (in_array($group['status'], ['new', 'inwork', 'returned' ])) {
        $inwork += $group['count'];
    } elseif ($group['status'] == 'pending') {
        $pending = $group['count'];
    } elseif ($group['status'] == 'postpone') {
        $postpone = $group['count'];
    } elseif ($group['status'] == 'overdue') {
        $overdue = $group['count'];
    }
    $all += $group['count'];
}

require_once __ROOT__ . '/engine/backend/functions/log-functions.php';
require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';
require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';


$events = getEventsForUser(21);
prepareEvents($events);

$firstDayOfMonth = strtotime(date('1.m.Y'));

$taskDoneCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT e.task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE company_id = :companyId AND datetime > :firstDay AND action = 'workdone'");
$taskDoneCountQuery->bindValue(':firstDay', (int) $firstDayOfMonth, PDO::PARAM_INT);
$taskDoneCountQuery->bindValue(':companyId', (int) $idc, PDO::PARAM_INT);
$taskDoneCountQuery->execute();
$taskDoneCountQuery->bindValue(':firstDay', (int) $firstDayOfMonth, PDO::PARAM_INT);

$taskDoneCountCurrentMonth = $taskDoneCountQuery->fetch(PDO::FETCH_COLUMN);

$taskDoneCountQuery->bindValue(':firstDay', 1546300800, PDO::PARAM_INT);
$taskDoneCountQuery->execute();
$taskDoneCountOverall = $taskDoneCountQuery->fetch(PDO::FETCH_COLUMN);

$ceoTasksQuery = $pdo->prepare("SELECT DISTINCT t.id, t.worker AS idworker, t.manager AS idmanager, t.datecreate, 
                t.status, t.view_status, t.name, t.datedone, t.view, u.name AS managerName, u.surname AS managerSurname, 
                LOCATE( :quotedUserId, t.view_status) AS view_order FROM tasks t LEFT JOIN users u ON t.worker = u.id 
WHERE t.idcompany = :companyId AND t.status NOT IN ('done', 'canceled') AND ((t.worker = :userId AND t.manager = 1) OR (t.worker <> :userId AND t.manager > 1))
ORDER BY FIELD(t.status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, t.datedone LIMIT 3");
$ceoTasksQuery->execute(array(':companyId' => $idc, ':quotedUserId' => '"' . $id . '"', ':userId' => $id));
$tasks = $ceoTasksQuery->fetchAll(PDO::FETCH_ASSOC);

$countAllTasks = count($tasks);
if ($countAllTasks > 20) {
    unset($tasks[20]);
}
prepareTasks($tasks);

$startTime = strtotime('today - 6 days');
$endTime = time();
$offset = get_timezone_offset(date_default_timezone_get(), 'UTC');
$taskDoneCountSql = $pdo->prepare("SELECT COUNT(DISTINCT task_id) as count, e.datetime - e.datetime%(60*60*24) + :timeZoneOffset as period FROM events e WHERE e.datetime between :startTime AND :endTime AND e.action = 'workdone' and e.company_id = :companyId GROUP BY period");
$taskDoneCountSql->bindValue(':timeZoneOffset', (int) $offset, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':startTime', (int) $startTime, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':endTime', (int) $endTime, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':companyId', (int) $idc, PDO::PARAM_INT);
$taskDoneCountSql->execute();
$taskDoneCountResult = $taskDoneCountSql->fetchAll(PDO::FETCH_ASSOC);
$taskDoneCount = [];
$t = $startTime;
foreach ($taskDoneCountResult as $count) {
    while ($t < $count['period']) {
        $taskDoneCount[] = 0;
        $t += 60 * 60 * 24;
    }
    $taskDoneCount[] = (int) $count['count'];
    $t += 60 * 60 * 24;
}
while (count($taskDoneCount) < 8 && $t <= $endTime) {
    $taskDoneCount[] = 0;
    $t += 60 * 60 * 24;
}
$taskCountString = implode(',', $taskDoneCount);
$dataForChart = [];
for ($i = 0; $i < 7; $i++){
    $dataForChart[] = '[\'' . date('d.m', $startTime + 3600 * 24 * $i) . '\' ' . ']';
}
$dataForChartString = implode(',', $dataForChart);

$userRegisterDate = DBOnce('register_date', 'users', 'id = ' . $id);

$firstDayOfPreviousMonth = (strtotime('first day of previous month midnight'));
$lastDayPreviousMonth = strtotime('last day of -1 month');
$thisDayPreviousMonth = strtotime('-1 month');
if ($lastDayPreviousMonth < $thisDayPreviousMonth) {
    $currentDayPreviousMonth = strtotime('midnight +1 day', $lastDayPreviousMonth);
} else {
    $currentDayPreviousMonth = strtotime('midnight +1 day', $thisDayPreviousMonth);
}
if ($userRegisterDate <= $currentDayPreviousMonth) {
    $taskDoneSamePeriodPreviousMonthQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE company_id = :companyId AND e.action = 'workdone' AND e.datetime > :firstDayPreviousMonth AND e.datetime <:currentDayPreviousMonth");
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':firstDayPreviousMonth', (int)$firstDayOfPreviousMonth, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':currentDayPreviousMonth', (int)$currentDayPreviousMonth, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':companyId', (int)$idc, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->execute();
    $taskDoneSamePeriodCount = $taskDoneSamePeriodPreviousMonthQuery->fetch(PDO::FETCH_COLUMN);

    $taskDoneDelta = $taskDoneCountCurrentMonth - $taskDoneSamePeriodCount;
} else {
    $taskDoneDelta = null;
}
if ($taskDoneDelta > 0) {
    $taskDoneDelta = '+' . $taskDoneDelta;
}
$isFirstLogin = false;
if (isset($_SESSION['isFirstLogin']) && $_SESSION['isFirstLogin']) {
    $isFirstLogin = true;
    $companyName = $_SESSION['companyName'];
    $email = $_SESSION['login'];
    $password = $_SESSION['password'];
    unset($_SESSION['isFirstLogin']);
    unset($_SESSION['companyName']);
    unset($_SESSION['login']);
    unset($_SESSION['password']);
}

//Блок данных для мини-игры за промокод
if ($roleu == 'ceo' && !checkPromocodeForUsedByCompany($idc, 'lusygame')) {
    $showGame = true;
    $stepProfile = false;
    $stepTaskCreate = false;
    $stepTaskDone = false;
    if (isset($nameu) && $nameu != '') {
        $stepProfile = true;
    }
    $taskGameQuery = $pdo->prepare("SELECT COUNT(DISTINCT id) AS count, status FROM tasks WHERE manager = :userId GROUP BY status");
    $taskGameQuery->execute([':userId' => $id]);
    $taskGame = $taskGameQuery->fetchAll(PDO::FETCH_ASSOC);
    foreach ($taskGame as $taskGroup) {
        if ($taskGroup['count'] > 0) {
            $stepTaskCreate = true;
        }
        if ($taskGroup['status'] == 'done' && $taskGroup['count'] > 0) {
            $stepTaskDone = true;
        }
    }
    $stepProgress = (int)$stepProfile + (int)$stepTaskCreate + (int)$stepTaskDone;
    $isGameCompleted = ($stepProgress == 3) ? true : false;
    $stepProgressBar = 100 * $stepProgress / 3;
    var_dump($stepProgress);
    $stepContent = [
        'create' => [
            'link' => '/task/new/',
            'icon' => 'fas fa-clipboard fa-fw',
            'text' => 'Создать<br/>задачу',
            'doneStep' => ($stepTaskCreate)? 'doneStep' : '',
        ],
        'done' => [
            'link' => '/tasks/',
            'icon' => 'fas fa-clipboard-check fa-fw',
            'text' => 'Завершить задачу',
            'doneStep' => ($stepTaskDone)? 'doneStep' : '',
        ],
    ];
    $stepProfileContent = [
        'profile' => [
            'link' => '/settings/',
            'icon' => 'fas fa-user fa-fw',
            'text' => 'Заполнить профиль',
            'doneStep' => ($stepProfile)? 'doneStep' : '',
        ],
    ];

    if ($stepTaskCreate && !$stepProfile) {
        $stepOrder = ['create', 'done', 'profile'];
        $stepContent = $stepContent + $stepProfileContent;
    } else {
        $stepOrder = ['profile', 'create', 'done'];
        $stepContent = $stepProfileContent + $stepContent;
    }
}
