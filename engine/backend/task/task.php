<?php
// session_start();




$id_task = $_GET['task'];
$id = $GLOBALS["id"];
global $pdo;
$task = DB('id, name, status, description, manager, worker, view, datecreate, datedone','tasks','id = "'.$id_task.'" LIMIT 1');
foreach ($task as $n) { 
$idtask = $n['id'];
$nametask = $n['name'];
$description = $n['description'];
$manager = $n['manager']; $managername = DBOnce('name','users','id='.$manager); $managersurname = DBOnce('surname','users','id='.$manager);
$worker = $n['worker']; $workername = DBOnce('name','users','id='.$worker); $workersurname = DBOnce('surname','users','id='.$worker);
$view = $n['view'];
$datecreate = date("d.m.Y", strtotime($n['datecreate']));
$datedone = date("d.m.Y", strtotime($n['datedone']));
$nowdate = date("d.m.Y");
$dayost = (strtotime ($datedone)-strtotime ($nowdate))/(60*60*24);

$status = $n['status'];
if ($status == 'new') {$border = 'border-primary'; $icon = '<i class="fas fa-bolt text-warning"></i>';$color = 'text-primary';}
if ($status == 'overdue') {$border = 'border-danger'; $icon = '<i class="fab fa-gripfire text-danger"></i>';$color = 'text-danger';}
if ($status == 'postpone') {
	if ($dayost<0) {
		$border = 'border-danger'; $icon = '<i class="fab fa-gripfire text-warning"></i>';$color = 'text-danger';
	} else {
		$border = 'border-primary'; $icon = '<i class="fab fa-gripfire text-warning"></i>';$color = 'text-primary';	}
	}
	
if ($status == 'pending') {$border = 'border-success'; $icon = '<i class="fas fa-eye text-success"></i>';$color = 'text-success';}
// тестовое размещение иконки ***
if ($status == 'done') {$border = 'border-primary'; $icon = '<i class="fas fa-bolt text-warning"></i>';$color = 'text-primary';}
if ($status == 'returned') {$border = 'border-primary'; $icon = '<i class="fas fa-bolt text-warning"></i>';$color = 'text-primary';}
if ($status == 'inwork') {$border = 'border-primary'; $icon = '<i class="fas fa-bolt text-warning"></i>';$color = 'text-primary';}
//тестовое размещение иконки ***
}


if ($id == $worker and $view == 0) {
	$viewer = $pdo->prepare('UPDATE `tasks` SET view = "1" where id="'.$idtask.'"');
	$viewer->execute();
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
