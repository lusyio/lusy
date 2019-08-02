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


$events = getEventsForUser(21);
prepareEvents($events);

$newtask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "new" and worker='.$id);
$overduetask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "overdue" and worker='.$id);
$completetask = DBOnce('COUNT(*) as count','tasks','status = "done" and (worker='.$id.' or manager='.$id.')');

$newtask2 = DB('*','tasks','view="0" and status = "new" and worker='.$id);



$usertasks = DB('id','tasks','worker='.$id.' or manager='.$id);
$idtasks = [];
foreach ($usertasks as $n) {
    $idtasks[] = $n["id"];
}
$ids = join('","',$idtasks);
$comments2 = DB('*','comments','view="0" and idtask IN ("'.$ids.'") and iduser !='.$id.' order by id desc');
$comments = DBOnce('COUNT(*) as count','comments','view="0" and idtask IN ("'.$ids.'") and iduser !='.$id);

$overduetask2 = DB('*','tasks','view="0" and status = "overdue" and worker='.$id);
$completetask2 = DB('*','tasks','view="0" and status = "done" and worker='.$id);

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

$ceoTasksQuery = $pdo->prepare("SELECT DISTINCT t.id, t.worker AS idworker, t.manager AS idmanager, t.datecreate, t.status, t.view_status, t.name, t.datedone, t.view, u.name AS managerName, u.surname AS managerSurname, LOCATE( :quotedUserId, t.view_status) AS view_order FROM tasks t LEFT JOIN users u ON t.worker = u.id WHERE t.idcompany = :companyId AND t.status NOT IN ('done', 'canceled') ORDER BY FIELD(t.status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, t.datedone LIMIT 3");
$ceoTasksQuery->execute(array(':companyId' => $idc, ':quotedUserId' => '"' . $id . '"'));
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