<?php
global $id;
global $pdo;
global $cometHash;
global $cometTrackChannelName;

require_once 'engine/backend/functions/log-functions.php';

$systemEvents = [
    'sendInvite', 'newUserRegistered', 'newCompanyRegistered',
];

$events = getEventsForUser();

foreach ($events as &$event) {
    $event['link'] = '';
    if ($event['action'] == 'comment') {
        $event['link'] = 'task/' . $event['task_id'] . '/#' . $event['commentId'];
    } else if ($event['action'] == 'newUserRegistered') {
        $event['link'] = 'profile/' . $event['commentId'] . '/';
        $event['name'] = DBOnce('name', 'users', 'id = ' . $event['commentId']);
        $event['surname'] = DBOnce('surname', 'users', 'id = ' . $event['commentId']);
    } else {
        $event['link'] = 'task/' . $event['task_id'] . '/';
    }
}
unset($event);

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
