<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');
$cron = true;

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

$stats = [];
$firstDay = strtotime('first day of this month midnight');
$companies = DB('id', 'company', 'id > 0');
$companiesTaskDoneCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT t.id) AS taskDone, t.idcompany AS companyId FROM tasks t LEFT JOIN events e ON t.id = e.task_id WHERE e.action = 'workdone' AND e.datetime >= :firstDay GROUP BY t.idcompany");
$companiesTaskDoneCountQuery->execute([':firstDay' => $firstDay]);
$companiesTaskDoneCount = $companiesTaskDoneCountQuery->fetchAll(PDO::FETCH_ASSOC);
$companiesOverdueCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id, datetime) AS overdue, company_id AS companyId FROM events WHERE action = 'overdue' AND datetime >= :firstDay GROUP BY company_id");
$companiesOverdueCountQuery->execute([':firstDay' => $firstDay]);
$companiesOverdueCount = $companiesOverdueCountQuery->fetchAll(PDO::FETCH_ASSOC);
$companiesCommentCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT c.id) AS comment, u.idcompany AS companyId FROM comments c LEFT JOIN users u ON c.iduser = u.id WHERE c.status = 'comment' AND c.datetime > :firstDay AND c.iduser > 1 GROUP BY u.idcompany");
$companiesCommentCountQuery->execute([':firstDay' => $firstDay]);
$companiesCommentCount = $companiesCommentCountQuery->fetchAll(PDO::FETCH_ASSOC);
$companiesMailCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT m.message_id) as message, u1.idcompany AS companyId FROM mail m LEFT JOIN users u1 ON m.sender = u1.id WHERE sender > 1 AND recipient > 1 AND datetime > :firstDay GROUP BY u1.idcompany");
$companiesMailCountQuery->execute([':firstDay' => $firstDay]);
$companiesMailCount = $companiesMailCountQuery->fetchAll(PDO::FETCH_ASSOC);
$companiesUserCountQuery = $pdo->prepare("SELECT COUNT(*) as users, idcompany AS companyId FROM users WHERE is_fired = 0 AND id > 1 GROUP BY idcompany");
$companiesUserCountQuery->execute([':firstDay' => $firstDay]);
$companiesUserCount = $companiesUserCountQuery->fetchAll(PDO::FETCH_ASSOC);
foreach ($companies as $company) {
    $stats[$company['id']] = [
        'taskDone' => 0,
        'overdue' => 0,
        'comment' => 0,
        'message' => 0,
        'score' => 0,
        'id' => $company['id'],
    ];
}
foreach ($companiesTaskDoneCount as $td) {
    $stats[$td['companyId']]['taskDone'] = $td['taskDone'];
}
foreach ($companiesOverdueCount as $ov) {
    $stats[$ov['companyId']]['overdue'] = $ov['overdue'];
}
foreach ($companiesCommentCount as $c) {
    $stats[$c['companyId']]['comment'] = $c['comment'];
}
foreach ($companiesMailCount as $m) {
    $stats[$m['companyId']]['message'] = $m['message'];
}
foreach ($companiesUserCount as $u) {
    $stats[$u['companyId']]['users'] = $u['users'];
}
foreach ($stats as $companyId => $values) {
    $stats[$companyId]['score'] = floor($values['taskDone'] - 10 * $values['overdue'] + 0.1 * ($values['comment'] + $values['message']));
}
usort($stats, function ($a, $b) {
    return $b['score'] - $a['score'];
});

$chartWinner = array_shift($stats);
$addChartWinnerQuery = $pdo->prepare("INSERT INTO chart_winners(period, company_id, points) VALUES (:period, :companyId, :points)");
    $addChartWinnerQuery->execute([
        ':period' => date('Y-m'),
        ':companyId' => $chartWinner['id'],
        ':points' => $chartWinner['score'],
    ]);


