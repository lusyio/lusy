<?php
global $id;
global $_overdue;
global $_pending;
global $_inprogress;
global $_history;
global $pdo;
global $cometHash;
global $cometTrackChannelName;


$worker = DBOnce('COUNT(*)','tasks','worker='.$id);
$manager = DBOnce('COUNT(*)','tasks','manager='.$id);
$all = $worker + $manager;
$inwork = DBOnce('COUNT(*) as count','tasks','(status="new" or status="inwork" or status="returned") and worker='.$id);
$pending = DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="pending"');
$overdue = DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="overdue"');


require_once 'engine/backend/functions/log-functions.php';

$events = getEventsForUser();
prepareEvents($events);

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
// функция добавления записи в лог
function newLog($action,$idtask,$comment,$sender,$recipient) {
    global $pdo;
    global $id;
    global $idc;
    global $datetime;

    if (empty($idtask)) {$idtask = 0;}
    if (empty($comment)) {$comment = 0;}

    $intolog = $pdo->prepare("INSERT INTO log SET action = :action, task = :idtask, comment = :comment, sender = :sender, recipient = :recipient, idcompany = :idcompany, datetime = :datetime");
    $intolog->execute(array('action' => $action, 'idtask' => $idtask, 'comment' => $comment, 'sender' => $sender, 'recipient' => $recipient, 'idcompany' => $idc, 'datetime' => $datetime));
}