<?php
global $id;
global $idc;
global $pdo;
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/', '', $url);
$url = str_replace('tasks', '', $url);
if(empty($url)) {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status!="done"';
}
if($url == 'inbox') {
	$otbor = 'worker='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'outbox') {
	$otbor = 'manager='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'new' or $url == 'returned' or $url == 'inwork') {
	$otbor = '(status = "new" or status = "inwork" or status = "returned" or status = "overdue") and (worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].')';
}
if($url == 'overdue' or $url == 'pending' or $url == 'done' or $url == 'postpone' ) {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status="'.$url.'"';
}
if ($url == 'canceled') {
	$otbor = 'status="canceled" and (worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ')';
}
//$tasks = DB('*','tasks',$otbor . ' order by datedone');
$tasksQuery = "SELECT t.id AS idtask, (SELECT GROUP_CONCAT(tc.worker_id) FROM task_coworkers tc where tc.task_id = t.id) AS taskCoworkers,
       t.name, t.description, t.datecreate, t.datedone, t.datepostpone, t.status, t.manager AS idmanager, t.worker AS idworker, t.idcompany, t.report, t.view,
       IF(t.datepostpone IS NULL OR t.datepostpone='0000-00-00', t.datedone, t.datepostpone) AS sort_date,
       (SELECT COUNT(*) FROM comments c WHERE c.status='comment' AND c.idtask = t.id) AS countcomments,
       (SELECT COUNT(*) FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE (u.comment_type='task' AND u.comment_id=t.id) OR c.idtask=t.id) as countAttachedFiles
FROM tasks t
WHERE manager=:userId OR worker=:userId ORDER BY sort_date";
$dbh = $pdo->prepare($tasksQuery);
$dbh->execute(array(':userId' => $id));
$tasks = $dbh->fetchAll(PDO::FETCH_ASSOC);
prepareTasks($tasks);
$usedStatuses = DB('DISTINCT `status`', 'tasks', $otbor);
$sortedUsedStatuses = getSortedStatuses($usedStatuses);

function prepareTasks(&$tasks)
{
	global $id;
	global $_months;
	foreach ($tasks as &$task) {
		if (!is_null($task['datepostpone']) && $task['datepostpone'] != '0000-00-00') {
			$task['datedone'] = $task["datepostpone"];
		}
		$task['dateProgress'] = getDateProgress($task['datedone'], $task['datecreate']);
		$task['deadLineDay'] = date('j', strtotime($task['datedone']));
		$task['deadLineMonth'] = $_months[date('n', strtotime($task['datedone'])) - 1];
		$task['classRole'] = '';
		if ($task['idworker'] == $id) {
			$task['classRole'] .= ' worker';
			$task['mainRole'] = 'worker';
		}
		if ($task['idmanager'] == $id) {
			$task['classRole'] .= ' manager';
			$task['mainRole'] = 'manager';

		}
		$task['coworkers'] = explode(',', $task['taskCoworkers']);
		$task['countCoworkers'] = count($task['coworkers']);

	}
}
$isManager = DBOnce('id', 'tasks', 'manager='.$GLOBALS["id"]);
$isWorker = DBOnce('id', 'tasks', 'worker='.$GLOBALS["id"]);

function getDateProgress($finishDate, $createDate)
{
	$dateCreateDateDoneDiff = strtotime($finishDate) - strtotime($createDate);
	if (strtotime($finishDate) > time()) {
		$daysTotal = $dateCreateDateDoneDiff / (60 * 60 * 24) + 1;
		$daysPassed = ceil((time() - strtotime($createDate)) / (60 * 60 * 24));
		return round(($daysPassed) * 100 / $daysTotal);
	} else {
		return 100;
	}
}

function getSortedStatuses($usedStatuses) {
	$result = [];
	foreach ($usedStatuses as $status) {
		$result[$status[0] . "filter"] = $GLOBALS["_{$status[0]}filter"];
	}
	asort($result);
	return $result;
}
?>


