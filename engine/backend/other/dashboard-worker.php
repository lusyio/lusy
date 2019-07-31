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


require_once __ROOT__ . '/engine/backend/functions/log-functions.php';
require_once __ROOT__ . '/engine/backend/functions/tasks-functions.php';

$countTaskQuery = $pdo->prepare("SELECT COUNT(DISTINCT t.id) FROM tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE t.status NOT IN ('planned', 'done', 'canceled') AND (t.worker = :userId OR t.manager = :userId OR tc.worker_id = :userId)");
$countTaskQuery->execute([':userId' => $id]);
$all = $countTaskQuery->fetch(PDO::FETCH_COLUMN);
$inwork = DBOnce('COUNT(DISTINCT t.id)','tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id','(status="new" or status="inwork" or status="returned") and (worker='.$id.' or manager='.$id.')');
$pending = DBOnce('COUNT(DISTINCT t.id)','tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id','(worker='.$id.' or manager='.$id.' or tc.worker_id = '.$id.') and status="pending"');
$postpone = DBOnce('COUNT(DISTINCT t.id)','tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id','(worker='.$id.' or manager='.$id.' or tc.worker_id = '.$id.') and status="postpone"');
$overdue = DBOnce('COUNT(DISTINCT t.id)','tasks t LEFT JOIN task_coworkers tc ON t.id = tc.task_id','(worker='.$id.' or manager='.$id.' or tc.worker_id = '.$id.') and status="overdue"');

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

$taskDoneCountOverallQuery = $pdo->prepare("SELECT COUNT(DISTINCT e.task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE (t.manager = :userId OR t.worker = :userId) AND datetime > :firstDay AND action = 'workdone'");
$taskDoneCountOverallQuery->bindValue(':firstDay', (int) $firstDayOfMonth, PDO::PARAM_INT);
$taskDoneCountOverallQuery->bindValue(':companyId', (int) $idc, PDO::PARAM_INT);
$taskDoneCountOverallQuery->bindValue(':userId', (int) $id, PDO::PARAM_INT);
$taskDoneCountOverallQuery->execute();
$taskDoneCountOverall = $taskDoneCountOverallQuery->fetch(PDO::FETCH_COLUMN);

$workerTasksQuery = $pdo->prepare("SELECT DISTINCT t.id, t.worker AS idworker, t.manager AS idmanager, t.datecreate, t.status, t.view_status, t.name, t.datedone, t.view, u.name AS managerName, u.surname AS managerSurname, LOCATE( :quotedUserId, t.view_status) AS view_order FROM tasks t LEFT JOIN users u ON t.worker = u.id LEFT JOIN task_coworkers tc ON t.id = tc.task_id WHERE (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId) AND t.status NOT IN ('done', 'canceled') AND (t.status <> 'planned' OR t.manager = :userId) ORDER BY FIELD(t.status, 'pending', 'postpone') DESC, FIELD(view_order, 0) DESC, t.datedone LIMIT 3");
$workerTasksQuery->execute(array(':companyId' => $idc, ':quotedUserId' => '"' . $id . '"', ':userId' => $id));
$tasks = $workerTasksQuery->fetchAll(PDO::FETCH_ASSOC);

$countAllTasks = count($tasks);
if ($countAllTasks > 20) {
    unset($tasks[20]);
}
prepareTasks($tasks);

$startTime = strtotime('today - 6 days');
$endTime = time();
$offset = get_timezone_offset(date_default_timezone_get(), 'UTC');
$taskDoneCountSql = $pdo->prepare("SELECT COUNT(DISTINCT task_id) as count, e.datetime - e.datetime%(60*60*24) + :timeZoneOffset as period FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE (t.manager = :userId OR t.worker = :userId) AND e.datetime between :startTime AND :endTime AND e.action = 'workdone' GROUP BY period");
$taskDoneCountSql->bindValue(':timeZoneOffset', (int) $offset, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':startTime', (int) $startTime, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':endTime', (int) $endTime, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':companyId', (int) $idc, PDO::PARAM_INT);
$taskDoneCountSql->bindValue(':userId', (int) $id, PDO::PARAM_INT);
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
    $taskDoneSamePeriodPreviousMonthQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id) FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE (t.manager = :userId OR t.worker = :userId) AND e.action = 'workdone' AND e.datetime > :firstDayPreviousMonth AND e.datetime <:currentDayPreviousMonth");
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':firstDayPreviousMonth', (int)$firstDayOfPreviousMonth, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':currentDayPreviousMonth', (int)$currentDayPreviousMonth, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->bindValue(':userId', (int)$id, PDO::PARAM_INT);
    $taskDoneSamePeriodPreviousMonthQuery->execute();
    $taskDoneSamePeriodCount = $taskDoneSamePeriodPreviousMonthQuery->fetch(PDO::FETCH_COLUMN);

    $taskDoneDelta = $taskDoneCountOverall - $taskDoneSamePeriodCount;
} else {
    $taskDoneDelta = null;
}
if ($taskDoneDelta > 0) {
    $taskDoneDelta = '+' . $taskDoneDelta;
}