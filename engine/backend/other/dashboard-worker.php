<?php
global $id;
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


$all = DBOnce('COUNT(*)','tasks','(status!="done" and status!="canceled") and (worker='.$id.' or manager='.$id.')');
$inwork = DBOnce('COUNT(*) as count','tasks','(status="new" or status="inwork" or status="returned") and (worker='.$id.' or manager='.$id.')');
$pending = DBOnce('COUNT(*) as count','tasks','(worker='.$id.' or manager='.$id.') and status="pending"');
$postpone = DBOnce('COUNT(*) as count','tasks','(worker='.$id.' or manager='.$id.') and status="postpone"');
$overdue = DBOnce('COUNT(*) as count','tasks','(worker='.$id.' or manager='.$id.') and status="overdue"');
$done = DBOnce('COUNT(*) as count','tasks','(worker='.$id.' or manager='.$id.') and status="done"');


require_once 'engine/backend/functions/log-functions.php';

$userData = getUserData($id);
$events = getEventsForUser();
prepareEvents($events);

$lastWeekCommentsQuery = $pdo->prepare("SELECT COUNT(*) FROM comments WHERE status = 'comment' AND iduser = :userId AND datetime > :datetime");
$lastWeekCommentsQuery->bindValue(':datetime', strtotime('monday this week'), PDO::PARAM_INT);
$lastWeekCommentsQuery->bindValue(':userId', $id, PDO::PARAM_INT);
$lastWeekCommentsQuery->execute();
$lastWeekComments = $lastWeekCommentsQuery->fetch(PDO::FETCH_COLUMN);

$lastWeekTasksCompletedCountQuery = $pdo->prepare("SELECT COUNT(DISTINCT e.task_id) FROM events e LEFT JOIN  tasks t ON t.id = e.task_id WHERE t.worker = :workerId AND e.action = 'workdone' AND e.datetime > :datetime");
$lastWeekTasksCompletedCountQuery->bindValue(':datetime', strtotime('monday this week'), PDO::PARAM_INT);
$lastWeekTasksCompletedCountQuery->bindValue(':workerId', $id, PDO::PARAM_INT);
$lastWeekTasksCompletedCountQuery->execute();
$lastWeekTasksCompletedCount = $lastWeekTasksCompletedCountQuery->fetch(PDO::FETCH_COLUMN);



$newtask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "new" and worker='.$id);
$overduetask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "overdue" and worker='.$id);
$completetask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "done" and worker='.$id);

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


