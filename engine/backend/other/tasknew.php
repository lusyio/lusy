<?php


global $id;

// функция создания задач
// try {
// function createTask($name,$description,$worker,$datedone) {
// 		global $pdo;
// 		global $now;
// 		global $id;
// 		global $idc;

		
// 		$dbh = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status,  manager, worker, idcompany, report, view) VALUES (:name, :description,'".$now."', :datedone, '".$now."', 'new', '".$id."', :worker, '".$idc."', :description, '1') ";
// 		$sql = $pdo->prepare($dbh);
// 		$sql->execute(array('name' => $name, 'description' => $description, 'worker' => $worker, 'datedone' => $datedone));
// 			if (!empty($idtask)) {
// 				newLog('createtask',$idtask,'',$id,$worker);
// 			}
// 		}
		
// 		// 		var_dump($sql);
// 		// }

// if (!empty($_POST['name']) and !empty($_POST['description']) and !empty($_POST['worker'])) {

// createTask($_POST['name'],$_POST['description'],$_POST['worker'],$_POST['datedone']);
// }
//   } catch(PDOException $e) {
//          echo $e->getMessage();
//      }