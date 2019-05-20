<?php
global $id;
global $pdo;
global $cometHash;
global $cometTrackChannelName;

$eventsQuery = $pdo->prepare('SELECT e.action, e.task_id, t.name AS taskName, e.author_id, u.name, u.surname, e.comment, c.comment AS commentText, e.datetime, e.view_status FROM events e
  LEFT JOIN tasks t ON t.id = e.task_id
  LEFT JOIN users u on u.id = e.author_id
  LEFT JOIN comments c on c.id = e.comment                                                                              
  WHERE e.recipient_id = :userId');
$eventsQuery->execute(array(':userId' =>$id));
$events = $eventsQuery->fetchAll(PDO::FETCH_ASSOC);

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
