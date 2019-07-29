<?php

require_once __ROOT__ . '/engine/backend/functions/task-functions.php';

global $idc;
global $roleu;
global $tariff;

$tryPremiumLimits = getFreePremiumLimits($idc);
$isManager = false;
$isWorker = false;
$usePremiumTask = false;
$usePremiumCloud = false;
if (isset($_POST['it'])) {
    $idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
    $taskAuthorId =  DBOnce('author', 'tasks', 'id='.$idtask);
    $idTaskManager = DBOnce('manager', 'tasks', 'id='.$idtask);
    $idTaskWorker = DBOnce('worker', 'tasks', 'id='.$idtask);
    $taskDatedone = DBOnce('datedone', 'tasks', 'id='.$idtask);
    $checklist = json_decode(DBOnce('checklist', 'tasks', 'id='.$idtask), true);
    if ($id == $idTaskManager) {
        $isManager = true;
    }
    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $idtask));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN, 0);
    if($id == $idTaskWorker || in_array($id, $coworkers)) {
        $isWorker = true;
    }
    $taskStatus = DBOnce('status', 'tasks', 'id='.$idtask);

}

if ($roleu == 'ceo') {
    $isManager = true;
}

if($_POST['module'] == 'sendonreview' && $isWorker) {
    $report = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }

    setStatus($idtask, 'pending');
    $commentId = addSendOnReviewComments($idtask, $report);

    if (count($_FILES) > 0) {
        uploadAttachedFiles('comment', $commentId);
    }
    if (count($googleFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addGoogleFiles('comment', $commentId, $googleFiles);
        $usePremiumCloud = true;
    }
    if (count($dropboxFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addDropboxFiles('comment', $commentId, $dropboxFiles);
        $usePremiumCloud = true;
    }
    if ($tariff == 0) {
        if ($usePremiumCloud) {
            updateFreePremiumLimits($idc, 'cloud');
        }
    }
    resetViewStatus($idtask);
    addEvent('review', $idtask, $commentId);
    if ($idTaskManager == 1) {
        checkSystemTask($idtask);
    }
}

if($_POST['module'] == 'sendpostpone' && $isWorker) {
    $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $datepostpone = filter_var($_POST['datepostpone'],FILTER_SANITIZE_SPECIAL_CHARS);
    $status = 'request:' . strtotime($datepostpone);

    setStatus($idtask, 'postpone');
    addPostponeComments($idtask, strtotime($datepostpone), $text);
    resetViewStatus($idtask);
    addEvent('postpone', $idtask, strtotime($datepostpone), $idTaskManager);

}


// Завершение задачи

if($_POST['module'] == 'workdone' && $isManager) {
    $subTasks = checkSubTasksForFinish($idtask);
    if ($subTasks['status']) {
        setFinalStatus($idtask, 'done');
        addFinalComments($idtask, 'done');
        resetViewStatus($idtask);
        if ($taskStatus != 'planned') {
            addEvent('workdone', $idtask, '');
        }
    } else {
        echo json_encode($subTasks);
    }
}

// Отмена задачи

if($_POST['module'] == 'cancelTask' && $isManager) {
    $subTasks = checkSubTasksForFinish($idtask);
    if ($subTasks['status']) {
        setFinalStatus($idtask, 'canceled');
        addFinalComments($idtask, 'canceled');
        resetViewStatus($idtask);
        if ($taskStatus != 'planned') {
            addEvent('canceltask', $idtask, '');
        }
    } else {
        echo json_encode($subTasks);
    }


}

// Кнопка вернуть для worker'a

if($_POST['module'] == 'workreturn' && $isManager) {
    $datepostpone = filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }

    setStatus($idtask, 'returned', strtotime($datepostpone));
    $commentId = addWorkReturnComments($idtask, strtotime($datepostpone), $text);

    if (count($_FILES) > 0) {
        uploadAttachedFiles('comment', $commentId);
    }
    if (count($googleFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addGoogleFiles('comment', $commentId, $googleFiles);
        $usePremiumCloud = true;
    }
    if (count($dropboxFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addDropboxFiles('comment', $commentId, $dropboxFiles);
        $usePremiumCloud = true;
    }

    if ($tariff == 0) {
        if ($usePremiumTask) {
            updateFreePremiumLimits($idc, 'task');
        }
        if ($usePremiumCloud) {
            updateFreePremiumLimits($idc, 'cloud');
        }
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
    $result = [
        'taskId' => '',
        'error' => '',
    ];
    $remainingLimits = getRemainingLimits();
    if ($remainingLimits['tasks'] <= 0) {
        $result['error'] = 'taskLimit';
        echo json_encode($result);
        exit;
    }
    $unsafeCoworkers = json_decode($_POST['coworkers']);
    $coworkers = [];
    foreach ($unsafeCoworkers as $c) {
        $coworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
    }
    $unsafeChecklist = json_decode($_POST['checklist'], true);
    $checklist = [];
    foreach ($unsafeChecklist as $key => $value) {
        $checklist[$key]['text'] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
        $checklist[$key]['status'] = 0;
    }
    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
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
    $status = 'new';
    $dateCreate = time();
    if (isset($_POST['startdate']) && ($tariff == 1 || $tryPremiumLimits['task'] < 3)) {
        $dateCreate = strtotime(filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS));
        if (date('Y-m-d', $dateCreate) > date('Y-m-d') && date('Y-m-d', $dateCreate) <= date('Y-m-d', $datedone)) {
            $status = 'planned';
            $usePremiumTask = true;
        }
    }
    $parentTask = filter_var($_POST['parentTask'], FILTER_SANITIZE_NUMBER_INT);

    $taskCreateQueryData = [
        ':name' => $name,
        ':description' => $description,
        ':dateCreate' => $dateCreate,
        ':author' => $id, ':manager' => $managerId,
        ':worker' => $worker,
        ':companyId' => $idc,
        ':datedone' => $datedone,
        ':status' => $status,
        ':parentTask' => null,
        ':checklist' => json_encode($checklist),
    ];
    if ($parentTask != '' && $parentTask != 0 && ($tariff == 1 || $tryPremiumLimits['task'] < 3)) {
        $parentTaskDataQuery = $pdo->prepare("SELECT manager, worker, idcompany FROM tasks WHERE id = :taskId");
        $parentTaskDataQuery->execute(['taskId' => $parentTask]);
        $parentTaskData = $parentTaskDataQuery->fetch(PDO::FETCH_ASSOC);
        if ($parentTaskData['manager'] == $id || $parentTaskData['worker'] == $id || ($roleu == 'ceo' && $parentTaskData['idcompany'] == $idc)) {
            $taskCreateQueryData[':parentTask'] = $parentTask;
            $usePremiumTask = true;
        }
    }
    $taskCreateQuery = $pdo->prepare("INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view, parent_task, checklist) VALUES (:name, :description, :dateCreate, :datedone, NULL, :status, :author, :manager, :worker, :companyId, :description, '0', :parentTask, :checklist)");
    $taskCreateQuery->execute($taskCreateQueryData);
    if ($taskCreateQuery) {
        $idtask = $pdo->lastInsertId();
        if (!empty($idtask)) {
            $result['taskId'] = $idtask;
            echo json_encode($result);
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
    if (count($googleFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addGoogleFiles('task', $idtask, $googleFiles);
        $usePremiumCloud = true;
    }
    if (count($dropboxFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
        addDropboxFiles('task', $idtask, $dropboxFiles);
        $usePremiumCloud = true;
    }
    if ($status != 'planned') {
        resetViewStatus($idtask);
        addTaskCreateComments($idtask, $worker, $coworkers);
        addEvent('createtask', $idtask, $datedone, $worker);
    } else {
        addEvent('createplantask', $idtask, $dateCreate, $worker);
    }
    if (!is_null($taskCreateQueryData[':parentTask'])) {
        addSubTaskComment($taskCreateQueryData[':parentTask'], $idtask);
        addNewSubTaskEvent($taskCreateQueryData[':parentTask'], $idtask);
    }

    if ($tariff == 0) {
        if ($usePremiumTask) {
            updateFreePremiumLimits($idc, 'task');
        }
        if ($usePremiumCloud) {
            updateFreePremiumLimits($idc, 'cloud');
        }
    }
}


//отклонение запроса на перенос срока

if ($_POST['module'] == 'cancelDate' && $isManager) {
    $sql = $pdo->prepare("UPDATE `tasks` SET `status` = 'inwork' WHERE id=" . $idtask); //TODO нужно разобраться со статусами
    $sql->execute();

    addChangeDateComments($idtask, 'canceldate');
    resetViewStatus($idtask);
    addEvent('canceldate', $idtask, $taskDatedone);

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
    $sql = $pdo->prepare("UPDATE `tasks` SET `status` = :status, datedone = :datepostpone, `view` = 0 WHERE id=".$idtask);

    if ($taskStatus != 'planned') {
        $sql->execute(array('datepostpone' => $datepostpone, ':status' => 'inwork'));
        addChangeDateComments($idtask, 'senddate', $datepostpone);
        resetViewStatus($idtask);
        addEvent('senddate', $idtask, $datepostpone);
    } else {
        if (date('d-m-Y', $datepostpone) == date('d-m-Y')) {
            resetViewStatus($idtask);
            addTaskCreateComments($idtask, $worker, $coworkers);
            addEvent('createtask', $idtask, $datedone, $worker);
        }else {
            $sql->execute(array('datepostpone' => $datepostpone, ':status' => 'planned'));
        }
    }
}

if ($_POST['module'] == 'changeStartDate' && $isManager) {
    $startDate = strtotime(filter_var($_POST['startDate'], FILTER_SANITIZE_SPECIAL_CHARS));
    $sql = $pdo->prepare("UPDATE `tasks` SET `status` = :status, datecreate = :startDate, `view` = 0 WHERE id=".$idtask);

    if ($startDate <= time()){
        $sql->execute(array(':startDate' => $startDate, ':status' => 'new'));
        resetViewStatus($idtask);
        addTaskCreateComments($idtask, $idTaskWorker, $coworkers);
        addEvent('createtask', $idtask, $datedone, $idTaskWorker);
    } else {
        $sql->execute(array(':startDate' => $startDate, ':status' => 'planned'));
    }
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
            if ($taskStatus != 'planned') {
                addChangeExecutorsComments($idtask, 'addcoworker', $newCoworker);
                addEvent('addcoworker', $idtask, '', $newCoworker);
            }
        }
    }
    $deleteCoworkerQuery = $pdo->prepare('DELETE FROM task_coworkers where task_id = :taskId AND worker_id = :coworkerId');
    foreach ($coworkers as $oldCoworker) {
        if (!in_array($oldCoworker, $newCoworkers)) { // удаляем соисполнителя, если его нет в новом списке соисполнителей
            $deleteCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $oldCoworker));
            if ($taskStatus != 'planned') {
                addChangeExecutorsComments($idtask, 'removecoworker', $oldCoworker);
                addEvent('removecoworker', $idtask, '', $oldCoworker);
            }
        }
    }

    $newWorker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
    if ($newWorker != $idTaskWorker) {
        $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
        $changeWorkerQuery->execute(array(':taskId' => $idtask, ':newWorker' => $newWorker));
        if ($taskStatus != 'planned') {
            addChangeExecutorsComments($idtask, 'newworker', $newWorker);
            addEvent('changeworker', $idtask, '', $idTaskWorker);
        }
    }
    if ($taskStatus != 'planned') {
        resetViewStatus($idtask);
    }
}

if ($_POST['module'] == 'checklist' && ($isManager || $id == $idTaskWorker) && isset($_POST['checklistRow'])) {
    $checklistRow = filter_var($_POST['checklistRow'], FILTER_SANITIZE_STRING);
    if ($checklist[$checklistRow] == 0) {
        $checklist[$checklistRow] = 1;
    } else {
        $checklist[$checklistRow] = 0;
    }
    $updateCheklistQuery = $pdo->prepare('UPDATE `tasks` SET checklist = :checklist WHERE id='.$idtask);
    $updateCheklistData = [
        ':checklist' => json_encode($checklist)
    ];
    $sql->execute($updateCheklistData);
    return $checklist[$checklistRow];
}

