<?php
	// 	global $pdo;
	// 	global $now;
	// 	global $id;
	// 	global $idc;
	// if (!empty($_POST['name']) and !empty($_POST['description']) and !empty($_POST['worker'])) {
	//     $sql = $pdo->prepare("INSERT INTO tasks SET name = :name, description = :description, datecreate = '".$now."', datedone = :datedone,status = 'new', manager = '".$id."', worker = :worker, idcompany = '".$idc."'");
	// 	$sql->execute(array('name' => $_POST['name'], 'description' => $_POST['description'], 'worker' => $_POST['worker'], 'datedone' => $_POST['datedone']));
	// 	var_dump($sql);

	// }

function createTask($name,$description,$worker,$datedone) {
		global $pdo;
		global $now;
		global $id;
		global $idc;
		
		$sql = $pdo->prepare("INSERT INTO tasks SET name = :name, description = :description, datecreate = '".$now."', datedone = :datedone,status = 'new', manager = '".$id."', worker = :worker, idcompany = '".$idc."'");
		$sql->execute(array('name' => $name, 'description' => $description, 'worker' => $worker, 'datedone' => $datedone));
		$idtask = $pdo->lastInsertId();
		
		if (!empty($idtask)) {
			echo $idtask;
		}else {
			echo "ошибка";
		}


			var_dump($sql);
	}

if (!empty($_POST['name']) and !empty($_POST['description']) and !empty($_POST['worker'])) {

createTask($_POST['name'],$_POST['description'],$_POST['worker'],$_POST['datedone']);

}