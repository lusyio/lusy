<?php
// session_start();




$id_task = $_GET['task'];
$id = $GLOBALS["id"];
global $pdo;
global $datetime;
$task = DB('id, name, status, description, manager, worker, view, datecreate, datedone, datepostpone','tasks','id = "'.$id_task.'" LIMIT 1');
foreach ($task as $n) { 
	$idtask = $n['id'];
	$nametask = $n['name'];
	$description = nl2br($n['description']);
	$manager = $n['manager'];
	$managername = DBOnce('name', 'users', 'id=' . $manager);
	$managersurname = DBOnce('surname', 'users', 'id=' . $manager);
	$worker = $n['worker'];
	$workername = DBOnce('name', 'users', 'id=' . $worker);
	$workersurname = DBOnce('surname', 'users', 'id=' . $worker);
	$view = $n['view'];
	$viewState = '';
	if ($view == 0) {
		$viewState = '(не просмотрено)';
	}
	$datecreate = date("d.m.Y", strtotime($n['datecreate']));
	$datedone = date("d.m", strtotime($n['datedone']));
	if ($n['datepostpone'] == '0000-00-00'|| $n['datepostpone'] == '') {
		$datepostpone = '';
	} else {
		$datepostpone = " >> " . date("d.m", strtotime($n['datepostpone']));
	}

	$nowdate = date("d.m.Y");
	$dayost = (strtotime($datedone) - strtotime($nowdate)) / (60 * 60 * 24);

	$status = $n['status'];

	$bg1 = 'bg-custom-color';
	$bg2 = 'bg-custom-color';
	$bg3 = 'bg-custom-color';
	$ic1 = 'fas fa-bolt';
	$ic2 = 'fas fa-eye';
	$ic3 = 'fas fa-check';

	$border = setTaskBorder($status);
	$icon = setTaskIcon($status);
	$color = setTaskIcon($status);

	switch ($status) {
		case 'new':
		case 'inwork':
			$bg1 = 'bg-primary';
			$ic1 = 'fas fa-bolt';
			break;
		case 'overdue':
			$bg1 = 'bg-danger';
			$ic1 = 'fab fa-gripfire';
			break;
		case 'postpone':
			$bg1 = 'bg-warning';
			$ic1 = 'far fa-clock';
			break;
		case 'pending':
			$bg2 = 'bg-warning';
			break;
		case 'done':
			$bg3 = 'bg-success';
			break;
		case 'returned':
		default:
	}
}

function setTaskBorder($status)
{
	global $dayost;
	switch ($status) {
		case 'new':
		case 'done':
		case 'returned':
		case 'inwork':
			return 'border-primary';
		case 'postpone':
			if ($dayost < 0) {
				return 'border-danger';
			} else {
				return 'border-primary';
			}
		case 'overdue':
			return 'border-danger';
		case 'pending':
			return 'border-success';
		default:
			return '';
	}
}

function setTaskColor($status)
{
	global $dayost;
	switch ($status) {
		case 'new':
		case 'done':
		case 'returned':
		case 'inwork':
			return 'text-primary';
		case 'postpone':
			if ($dayost < 0) {
				return 'text-danger';
			} else {
				return 'text-primary';
			}
		case 'overdue':
			return 'text-danger';
		case 'pending':
			return 'text-success';
		default:
			return '';
	}
}

function setTaskIcon($status)
{
	$result = '<i class="';
	switch ($status) {
		case 'new':
		case 'done':
		case 'returned':
		case 'inwork':
			$result .= 'fas fa-bolt text-warning';
			break;
		case 'postpone':
		case 'overdue':
			$result .= 'fab fa-gripfire text-warning';
			break;
		case 'pending':
			$result .= 'fas fa-eye text-success';
			break;
		default:
			return '';
	}
	$result .= '"></i>';
	return $result;
}


if ($id == $worker and $view == 0) {
	$viewer = $pdo->prepare('UPDATE `tasks` SET view = "1" where id="'.$idtask.'"');
	$viewer->execute();
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = 'Просмотрено', `iduser` = :iduser, `idtask` = :idtask, `status` = 'system', `view`=0, `datetime` = :datetime");
	$sql->execute(array('iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$points = DBOnce('points','users','id='.$id);
$points = $points + 5;
$viewer = $pdo->prepare('UPDATE `users` SET points = "'.$points.'" where id="'.$id.'"');
$viewer->execute();
}
$viewer = $pdo->prepare('UPDATE `comments` SET view = "1" where idtask="'.$id_task.'" and iduser!='.$id);
$viewer->execute();


//измменяем статус "в работе"


if ($id == $worker and $status == 'new' || $status == 'returned') {
	$viewer = $pdo->prepare('UPDATE `tasks` SET status = "inwork" where id="'.$idtask.'"');
	$viewer->execute();

}


// 

if ($id != $worker and $id != $manager) {
 echo "<script>document.location.href = '/tasks/'</script>";
} else {
if ($id == $worker) {
	$role = 'worker';
}
if ($id == $manager) {
	$role = 'manager';
}
if ($id == $worker and $id == $manager) {
	$role = 'manager';
}
// TODO много if'ов, лучше так:
//if ($id == $manager){
//	$role = 'manager';
//} elseif ($id == $worker){
//	$role = 'worker';
//} else {
//	echo "<script>document.location.href = '/tasks/'</script>";
//}

if ($status == 'overdue' || $status == 'new' || $status == 'returned') {
	$status = 'inwork';
}


// var_dump($role);
// var_dump($manager);
// var_dump($worker);

// раскидываем по статусам
// include 'engine/frontend/task/control/'.$role.'/'.$status.'.php';
// include 'engine/backend/task/task/control/'.$role.'/'.$status.'.php';
}



?>
