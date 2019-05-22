<?php

$isManager = false;
$isWorker = false;
if (isset($_POST['it'])) {
    $idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
    $taskAuthorId =  DBOnce('author', 'tasks', 'id='.$idtask);
    $idTaskManager = DBOnce('manager', 'tasks', 'id='.$idtask);
    if ($id == $idTaskManager) {
        $isManager = true;
    }
    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $idtask));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN, 0);
    if(in_array($id, $coworkers)) {
        $isWorker = true;
    }
}

if($_POST['module'] == 'sendonreview' && $isWorker) {
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "pending" WHERE id='.$idtask);
	$sql->execute();

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :report, `iduser` = :iduser, `idtask` = :idtask, `status` = 'report', `view`=0 ,`datetime` = :datetime");
	$sql->execute(array('report' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$commentId = $pdo->lastInsertId();

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
    }

    resetViewStatus($idtask);
	addEvent('review', $idtask, $taskAuthorId);
}

if($_POST['module'] == 'sendpostpone' && $isWorker) {
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);
	$datepostpone = filter_var($_POST['datepostpone'],FILTER_SANITIZE_SPECIAL_CHARS);
	$status = 'request:' . $datepostpone;

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "postpone" WHERE id='.$idtask);
	$sql->execute();

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :report, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");
	$sql->execute(array('report' => $text, 'iduser' => $id, 'idtask' => $idtask, 'status' => $status, 'datetime' => $datetime));

    resetViewStatus($idtask);
    addEvent('postpone', $idtask, $taskAuthorId);

}


// Кнопка принять для worker'a

if($_POST['module'] == 'workdone' && $isManager) {
	// $report = $_POST['report'];
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "done", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));

    resetViewStatus($idtask);
    addEvent('workdone', $idtask, $idTaskManager);

}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn' && $isManager) {
	$datepostpone = filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS);
	$text = filter_var($_POST['text'], FILTER_SANITIZE_SPECIAL_CHARS);

	$text .= "\nНовый срок: " . date("d.m", strtotime($datepostpone));

	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "returned", `view` = 0, `datepostpone` = :datepostpone WHERE id= :idtask');
	$sql->execute(array('datepostpone' => $datepostpone, 'idtask' => $idtask));

	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'returned', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
	$commentId = $pdo->lastInsertId();

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
	}
    resetViewStatus($idtask);
    addEvent('workreturn', $idtask, $idTaskManager);

}

// Кнопка В работу для worker'a

if($_POST['module'] == 'inwork') {
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "new" WHERE id='.$idtask);
	$sql->execute();
}

// создание новой задачи

if($_POST['module'] == 'createTask') {
    $unsafeCoworkers = json_decode($_POST['coworkers']);
    $coworkers = [];
    foreach ($unsafeCoworkers as $c) {
        $coworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
    }
    if (isset($_POST['manager'])) {
        $managerId = filter_var($_POST['manager'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $managerId = $id;
    }
    $coworkers = array_unique($coworkers, SORT_NUMERIC);
	$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	$description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
	$datedone = filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS);
	$worker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
	$query = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view) VALUES (:name, :description, :dateCreate, :datedone, NULL, 'new', :author, :manager, :worker, :companyId, :description, '0') ";
	$sql = $pdo->prepare($query);
	$sql->execute(array(':name' => $name, ':description' => $description, ':dateCreate' => $now, ':author' => $id, ':manager' => $managerId, ':worker' => $worker, ':companyId' => $idc, ':datedone' => $datedone));
	if ($sql) {
		$idtask = $pdo->lastInsertId();
		if (!empty($idtask)) {
			echo $idtask;
			$coworkersQuery = "INSERT INTO task_coworkers(task_id, worker_id) VALUES (:taskId, :workerId)";
            $cometSql = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newTask', :taskId)");
            $sql = $pdo->prepare($coworkersQuery);
            foreach ($coworkers as $workerId) {
                $sql->execute(array(':taskId' => $idtask, ':workerId' => $workerId));
                $cometSql->execute(array(':taskId' => $idtask, ':id' => $workerId));
            }
		}
	}
    if (count($_FILES) > 0) {
        uploadAttachedFiles('task', $idtask);
    }
    resetViewStatus($idtask);
    addEvent('createtask', $idtask, $managerId);
    addEvent('createtask', $idtask, $worker);

}

// отмена задачи

if($_POST['module'] == 'cancelTask' && $isManager) {
	$sql = $pdo->prepare('UPDATE `tasks` SET `status` = "canceled", `report` = :report WHERE id='.$idtask);
	$sql->execute(array('report' => $now));
	echo 'success';
    resetViewStatus($idtask);
    addEvent('canceltask', $idtask, $idTaskManager);

}

//отклонение запроса на перенос срока

if ($_POST['module'] == 'cancelDate' && $isManager) {
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = null WHERE id=" . $idtask); //TODO нужно разобраться со статусами
	$sql->execute();
	$text = "Перенос отклонен";
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
    resetViewStatus($idtask);
    addEvent('canceldate', $idtask, $idTaskManager);

}

//одобрение запроса на перенос срока

if ($_POST['module'] == 'confirmDate' && $isManager) {
	$statusWithDate = DBOnce('status', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
	$datepostpone = preg_split('~:~', $statusWithDate)[1];
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', `datepostpone` = :datepostpone WHERE id=" . $idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Перенос одобрен. Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
    resetViewStatus($idtask);
    addEvent('confirmdate', $idtask, $idTaskManager);

}

if ($_POST['module'] == 'sendDate' && $isManager) {
	$datepostpone = filter_var($_POST['sendDate'], FILTER_SANITIZE_SPECIAL_CHARS);
	$sql = $pdo->prepare('UPDATE `tasks` SET `datepostpone` = :datepostpone, `view` = 0 WHERE id='.$idtask);
	$sql->execute(array('datepostpone' => $datepostpone));
	$text = "Новый срок: " . date("d.m", strtotime($datepostpone));
	$sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :text, `iduser` = :iduser, `idtask` = :idtask, `status` = 'postpone', `view`=0, `datetime` = :datetime");
	$sql->execute(array('text' => $text, 'iduser' => $id, 'idtask' => $idtask, 'datetime' => $datetime));
    resetViewStatus($idtask);
    addEvent('senddate', $idtask, $idTaskManager);

}

if ($_POST['module'] == 'addCoworker' && $isManager) {
    $newCoworkerId = filter_var($_POST['newCoworkerId'], FILTER_SANITIZE_NUMBER_INT);
    if (!in_array($newCoworkerId, $coworkers)) {
        $addCoworkerQuery = $pdo->prepare("INSERT INTO task_coworkers SET task_id =:taskId, worker_id=:coworkerId");
        $addCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $newCoworkerId));
        resetViewStatus($idtask);
    }
}

function resetViewStatus($taskId) {
    global $id;
    global $pdo;
    global $datetime;
    $viewStatus = [];
    $viewStatus[$id]['datetime'] = $datetime;
    $viewStatusJson = json_encode($viewStatus);
    $viewQuery = $pdo->prepare('UPDATE `tasks` SET view_status = :viewStatus where id=:taskId');
    $viewQuery->execute(array(':viewStatus' => $viewStatusJson, ':taskId' => $taskId));
}

