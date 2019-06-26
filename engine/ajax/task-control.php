<?php

require_once 'engine/backend/functions/task-functions.php';

global $roleu;

$isManager = false;
$isWorker = false;
if (isset($_POST['it'])) {
    $idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
    $taskAuthorId =  DBOnce('author', 'tasks', 'id='.$idtask);
    $idTaskManager = DBOnce('manager', 'tasks', 'id='.$idtask);
    $idTaskWorker = DBOnce('worker', 'tasks', 'id='.$idtask);
    if ($id == $idTaskManager) {
        $isManager = true;
    }
    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $idtask));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN, 0);
    if($id == $idTaskWorker || in_array($id, $coworkers)) {
        $isWorker = true;
    }
}

if ($roleu == 'ceo') {
    $isManager = true;
}

if($_POST['module'] == 'sendonreview' && $isWorker) {
    $report = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    setStatus($idtask, 'pending');
    $commentId = addSendOnReviewComments($idtask, $report);

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
    }

    resetViewStatus($idtask);
	addEvent('review', $idtask, '');
}

if($_POST['module'] == 'sendpostpone' && $isWorker) {
	$text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$datepostpone = filter_var($_POST['datepostpone'],FILTER_SANITIZE_SPECIAL_CHARS);
	$status = 'request:' . strtotime($datepostpone);

	setStatus($idtask, 'postpone');
	addPostponeComments($idtask, strtotime($datepostpone), $text);
    resetViewStatus($idtask);
    addEvent('postpone', $idtask, '', $idTaskManager);

}


// Завершение задачи

if($_POST['module'] == 'workdone' && $isManager) {
    setFinalStatus($idtask, 'done');
    addFinalComments($idtask, 'done');
    resetViewStatus($idtask);
    addEvent('workdone', $idtask, '');

}

// Отмена задачи

if($_POST['module'] == 'cancelTask' && $isManager) {
    setFinalStatus($idtask, 'canceled');
    addFinalComments($idtask, 'canceled');
    resetViewStatus($idtask);
    addEvent('canceltask', $idtask, '');

}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn' && $isManager) {
	$datepostpone = filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS);
	$text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	setStatus($idtask, 'returned', strtotime($datepostpone));
	$commentId = addWorkReturnComments($idtask, strtotime($datepostpone), $text);

	if (count($_FILES) > 0) {
		uploadAttachedFiles('comment', $commentId);
	}
    resetViewStatus($idtask);
    addEvent('workreturn', $idtask, strtotime($datepostpone));

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
    $unsafeYandexFiles = json_decode($_POST['yaAttach']);
    $yandexFiles = [];
    foreach ($unsafeYandexFiles as $k => $v) {
        $yandexFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v, FILTER_SANITIZE_STRING),
            ];
    }
    $yaToken = filter_var($_POST['yaToken'], FILTER_SANITIZE_STRING);
    $unsafeGoogleFiles = json_decode($_POST['googleAttach']);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v, FILTER_SANITIZE_STRING),
        ];
    }
    if (isset($_POST['manager'])) {
        $managerId = filter_var($_POST['manager'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $managerId = $id;
    }
    $coworkers = array_unique($coworkers, SORT_NUMERIC);
	$name = trim($_POST['name']);
	$name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	$description = trim($_POST['description']);
	$description = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
	$datedone = strtotime(filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS));
	$worker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);

	$query = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view) VALUES (:name, :description, :dateCreate, :datedone, NULL, 'new', :author, :manager, :worker, :companyId, :description, '0') ";
	$sql = $pdo->prepare($query);
	$sql->execute(array(':name' => $name, ':description' => $description, ':dateCreate' => time(), ':author' => $id, ':manager' => $managerId, ':worker' => $worker, ':companyId' => $idc, ':datedone' => $datedone));
	if ($sql) {
		$idtask = $pdo->lastInsertId();
		if (!empty($idtask)) {
			echo $idtask;
			$coworkersQuery = "INSERT INTO task_coworkers(task_id, worker_id) VALUES (:taskId, :workerId)";
            $sql = $pdo->prepare($coworkersQuery);
            foreach ($coworkers as $workerId) {
                $sql->execute(array(':taskId' => $idtask, ':workerId' => $workerId));
            }
		}
	}
    if (count($_FILES) > 0) {
        uploadAttachedFiles('task', $idtask);
    }
    if (count($yandexFiles) > 0 && $yaToken != '') {
        addYandexFiles('task', $idtask, $yandexFiles, $yaToken);
    }
    if (count($googleFiles) > 0) {
        addGoogleFiles('task', $idtask, $googleFiles);
    }
    resetViewStatus($idtask);
    addTaskCreateComments($idtask, $worker, $coworkers);
    addEvent('createtask', $idtask, $datedone, $worker);

}


//отклонение запроса на перенос срока

if ($_POST['module'] == 'cancelDate' && $isManager) {
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork' WHERE id=" . $idtask); //TODO нужно разобраться со статусами
	$sql->execute();

    addChangeDateComments($idtask, 'canceldate');
    resetViewStatus($idtask);
    addEvent('canceldate', $idtask, '');

}

//одобрение запроса на перенос срока

if ($_POST['module'] == 'confirmDate' && $isManager) {
	$statusWithDate = DBOnce('status', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
	$datepostpone = preg_split('~:~', $statusWithDate)[1];
	setStatus($idtask, 'inwork', $datepostpone);

	addChangeDateComments($idtask, 'confirmdate', $datepostpone);
    resetViewStatus($idtask);
    addEvent('confirmdate', $idtask, $datepostpone);

}

if ($_POST['module'] == 'sendDate' && $isManager) {
	$datepostpone = strtotime(filter_var($_POST['sendDate'], FILTER_SANITIZE_SPECIAL_CHARS));
	$sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork', datedone = :datepostpone, `view` = 0 WHERE id=".$idtask);
	$sql->execute(array('datepostpone' => $datepostpone));

    addChangeDateComments($idtask, 'senddate', $datepostpone);
    resetViewStatus($idtask);
    addEvent('senddate', $idtask, $datepostpone);

}

if ($_POST['module'] == 'addCoworker' && $isManager) {
    $unsafeCoworkers = json_decode($_POST['coworkers']);
    $newCoworkers = [];
    foreach ($unsafeCoworkers as $c) {
        $newCoworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
    }
    $addCoworkerQuery = $pdo->prepare("INSERT INTO task_coworkers SET task_id =:taskId, worker_id=:coworkerId");
    foreach ($newCoworkers as $newCoworker) {
        if (!in_array($newCoworker, $coworkers)) { //добавляем соисполнителя, если его еще нет в таблице
            $addCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $newCoworker));
            addChangeExecutorsComments($idtask, 'addcoworker', $newCoworker);
            addEvent('addcoworker', $idtask, '', $newCoworker);
        }
    }
    $deleteCoworkerQuery = $pdo->prepare('DELETE FROM task_coworkers where task_id = :taskId AND worker_id = :coworkerId');
    foreach ($coworkers as $oldCoworker) {
        if (!in_array($oldCoworker, $newCoworkers)) { // удаляем соисполнителя, если его нет в новом списке соисполнителей
            $deleteCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $oldCoworker));
            addChangeExecutorsComments($idtask, 'removecoworker', $oldCoworker);
            addEvent('removecoworker', $idtask, '', $oldCoworker);
        }
    }

    $newWorker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
    if ($newWorker != $idTaskWorker) {
        $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
        $changeWorkerQuery->execute(array(':taskId' => $idtask, ':newWorker' => $newWorker));
        addChangeExecutorsComments($idtask, 'newworker', $newWorker);
        addEvent('changeworker', $idtask, '', $idTaskWorker);
    }

    resetViewStatus($idtask);
}



