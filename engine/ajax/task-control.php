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
    $taskDataQuery = $pdo->prepare("SELECT name, description, author, manager, worker, datecreate, datedone, checklist, status, parent_task FROM tasks WHERE id = :taskId");
    $taskDataQuery->execute([':taskId' => $idtask]);
    $taskData = $taskDataQuery->fetch(PDO::FETCH_ASSOC);
    $taskAuthorId =  $taskData['author'];
    $idTaskManager = $taskData['manager'];
    $idTaskWorker = $taskData['worker'];
    $taskDatedone = $taskData['datedone'];
    $checklist = json_decode($taskData['checklist'], true);
    if ($id == $idTaskManager || $idTaskManager == 1) {
        $isManager = true;
    }
    $coworkersQuery = $pdo->prepare("SELECT worker_id FROM task_coworkers WHERE task_id = :taskId");
    $coworkersQuery->execute(array(':taskId' => $idtask));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN, 0);
    if($id == $idTaskWorker || in_array($id, $coworkers)) {
        $isWorker = true;
    }
    $taskStatus = $taskData['status'];
    if (in_array($taskStatus, ['done', 'canceled'])) {
        exit;
    }
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
            echo json_encode($subTasks);
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
            echo json_encode($subTasks);
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
    $checklist = [];
    if (isset($_POST['checklist'])) {
        $unsafeChecklist = json_decode($_POST['checklist'], true);
        foreach ($unsafeChecklist as $key => $value) {
            $checklist[$key]['text'] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            $checklist[$key]['status'] = 0;
            $checklist[$key]['checkedBy'] = 0;
        }

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
    foreach ($coworkers as $key => $coworker) {
        if ($coworker == $managerId || $coworker == $worker) {
            unset($coworkers[$key]);
        }
    }
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
        ':withPremium' => 0,
    ];
    if ($parentTask != '' && $parentTask != 0 && ($tariff == 1 || $tryPremiumLimits['task'] < 3)) {
        $parentTaskDataQuery = $pdo->prepare("SELECT manager, worker, idcompany FROM tasks WHERE id = :taskId");
        $parentTaskDataQuery->execute(['taskId' => $parentTask]);
        $parentTaskData = $parentTaskDataQuery->fetch(PDO::FETCH_ASSOC);
        if ($parentTaskData['manager'] == $id || $parentTaskData['worker'] == $id || ($roleu == 'ceo' && $parentTaskData['idcompany'] == $idc)) {
            $taskCreateQueryData[':parentTask'] = $parentTask;
            $taskCreateQueryData[':withPremium'] = 1;
            $usePremiumTask = true;
        }
    }
    $taskCreateQuery = $pdo->prepare("INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view, parent_task, checklist, with_premium) VALUES (:name, :description, :dateCreate, :datedone, NULL, :status, :author, :manager, :worker, :companyId, :description, '0', :parentTask, :checklist, :withPremium)");
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
    if ($datepostpone == $taskDatedone) {
        exit;
    }
    setStatus($idtask, 'inwork', $datepostpone);

    addChangeDateComments($idtask, 'confirmdate', $datepostpone);
    resetViewStatus($idtask);
    addEvent('confirmdate', $idtask, $datepostpone);

}

if ($_POST['module'] == 'sendDate' && $isManager) {
    $datepostpone = strtotime(filter_var($_POST['sendDate'], FILTER_SANITIZE_SPECIAL_CHARS));
    if ($datepostpone == $taskDatedone) {
        exit;
    }
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
    if ($newWorker != $idTaskWorker && $newWorker != $idTaskManager) {
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

if ($_POST['module'] == 'checklist' && ($isManager || $isWorker) && isset($_POST['checklistRow'])) {
    $checklistRow = filter_var($_POST['checklistRow'], FILTER_SANITIZE_STRING);
    if ($checklist[$checklistRow]['status'] == 0 ) {
        $checklist[$checklistRow]['status'] = 1;
        $checklist[$checklistRow]['checkedBy'] = $id;
        $checklist[$checklistRow]['checkTime'] = time();
    } elseif ($isManager || ($checklist[$checklistRow]['checkedBy'] == $id && $checklist[$checklistRow]['checkTime'] > time() - 300)) {
        $checklist[$checklistRow]['status'] = 0;
        $checklist[$checklistRow]['checkTime'] = 0;
    } else {
        exit;
    }
    $updateChecklistQuery = $pdo->prepare('UPDATE `tasks` SET checklist = :checklist WHERE id='.$idtask);
    $updateChecklistData = [
        ':checklist' => json_encode($checklist)
    ];
    $updateChecklistQuery->execute($updateChecklistData);
    echo $checklist[$checklistRow]['status'];
}

// *********************** //
//  Редактирование задачи  //
// *********************** //

if($_POST['module'] == 'editTask' && $isManager) {
    //exit;
    $isPremiumUsed = false;
    $result = [
        'taskId' => '',
        'error' => '',
    ];
    // Не редактировать если задача от системного пользователя
    if ($taskData['manager'] == 1) {
        exit;
    }
    // Не редактировать если задача отменена или завершена
    if (in_array($taskData['status'], ['done', 'canceled'])) {
        exit;
    }
// Не редактировать если не премиум-тариф, не было премиум функций при создании задачи
// и лимит бесплатного премиума исчерпан
    if ($tariff == 0 && $tryPremiumLimits['edit'] >= 3) {
        exit;
    }

    require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

    // Изменилось ли название и описание
    $newName = trim($_POST['name']);
    $newName = filter_var($newName, FILTER_SANITIZE_SPECIAL_CHARS);
    $newDescription = trim($_POST['description']);
    $newDescription = filter_var($newDescription, FILTER_SANITIZE_SPECIAL_CHARS);
    if (($newName != $taskData['name']) || ($newDescription != $taskData['description'])) {
        $updateNameAndDescriptionQuery = $pdo->prepare("UPDATE tasks SET name = :name, description = :description WHERE id = :taskId");
        $updateNameAndDescriptionQuery->execute([':taskId' => $idtask, ':name' => $newName, ':description' => $newDescription]);
    }

    // Изменился ли дедлайн
    $newDatedone = strtotime(filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS));
    $nowDate = strtotime('midnight');
    if ($newDatedone != $taskDatedone && $newDatedone >= $nowDate) {
        $updateDatedoneQuery = $pdo->prepare("UPDATE tasks SET datedone = :datedone, status = :status WHERE id = :taskId");
        $updateDatedoneQuery->execute([':taskId' => $idtask, ':status' => 'inwork', ':datedone' => $newDatedone]);
        if ($taskData['status'] != 'planned') {
            $updateDatedoneQuery->execute([':taskId' => $idtask, ':datedone' => $newDatedone, ':status' => 'inwork']);
            addChangeDateComments($idtask, 'senddate', $newDatedone);
            resetViewStatus($idtask);
            addEvent('senddate', $idtask, $newDatedone);
        } else {
            $updateDatedoneQuery->execute(array('datepostpone' => $newDatedone, ':status' => 'planned'));
        }
    }

    // Изменился ли ответственный
    $newWorker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
    if ($newWorker != $taskData['worker']) {
        $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
        $changeWorkerQuery->execute(array(':taskId' => $idtask, ':newWorker' => $newWorker));
        if ($taskStatus != 'planned') {
            addChangeExecutorsComments($idtask, 'newworker', $newWorker);
            addEvent('changeworker', $idtask, '', $taskData['worker']);
            resetViewStatus($idtask);
        }
    }


    // Изменились ли соисполнители
    $unsafeCoworkers = json_decode($_POST['coworkers']);
    $editedCoworkers = [];
    foreach ($unsafeCoworkers as $c) {
        $editedCoworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
    }
    $coworkersToAdd = [];
    foreach ($editedCoworkers as $editedCoworker) {
        if (in_array($editedCoworker, $coworkers)) {
            continue;
        } else {
            $coworkersToAdd[] = $editedCoworker;
        }
    }
    $coworkersToRemove = [];
    foreach ($coworkers as $coworker) {
        if (in_array($coworker, $editedCoworkers)) {
            continue;
        } else {
            $coworkersToRemove[] = $coworker;
        }
    }
    if (count($coworkersToAdd) > 0) {
        $addCoworkerQuery = $pdo->prepare("INSERT INTO task_coworkers SET task_id =:taskId, worker_id=:coworkerId");
        foreach ($coworkersToAdd as $newCoworker) {
            $addCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $newCoworker));
            if ($taskStatus != 'planned') {
                addChangeExecutorsComments($idtask, 'addcoworker', $newCoworker);
                addEvent('addcoworker', $idtask, '', $newCoworker);
            }
        }
    }
    if (count($coworkersToRemove) > 0) {
        $deleteCoworkerQuery = $pdo->prepare('DELETE FROM task_coworkers where task_id = :taskId AND worker_id = :coworkerId');
        foreach ($coworkersToRemove as $oldCoworker) {
            $deleteCoworkerQuery->execute(array(':taskId' => $idtask, ':coworkerId' => $oldCoworker));
            if ($taskStatus != 'planned') {
                addChangeExecutorsComments($idtask, 'removecoworker', $oldCoworker);
                addEvent('removecoworker', $idtask, '', $oldCoworker);
            }
        }
    }

    // Редактирование надзадачи
    $newParentTask = filter_var($_POST['parentTask'], FILTER_SANITIZE_NUMBER_INT);
    if (is_null($taskData['parent_task']) && $newParentTask != 0) {
        //Назначение надзадачи
        addSubTaskComment($newParentTask, $idtask);
        addNewSubTaskEvent($newParentTask, $idtask);
    }
    if (!is_null($taskData['parent_task']) && $newParentTask == 0) {
        // Удаление надзадачи
    }
    if (!is_null($taskData['parent_task']) && $newParentTask != 0) {
        // Смена надзадачи
    }
    $updateParentTaskQuery = $pdo->prepare("UPDATE tasks SET parent_task = :parentTask WHERE id = :taskId");
    if ($newParentTask != 0) {
        if (is_null($taskData['parent_task']) || $newParentTask != $taskData['parent_task']) {
            $updateParentTaskQuery->execute([':taskId' => $idtask, ':parentTask' => $newParentTask]);
            addSubTaskComment($newParentTask, $idtask);
            addNewSubTaskEvent($newParentTask, $idtask);
        }
    } else {
        if (!is_null($taskData['parent_task'])) {
            $updateParentTaskQuery->execute([':taskId' => $idtask, ':parentTask' => null]);
        }
    }
    // Перезапись чеклиста
    $editedChecklist = [];
    if (isset($_POST['checklist'])) {
        $unsafeChecklist = json_decode($_POST['checklist'], true);
        foreach ($unsafeChecklist as $key => $value) {
            $editedChecklist[$key]['text'] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
            $editedChecklist[$key]['status'] = 0;
            $editedChecklist[$key]['checkedBy'] = 0;
        }
        $updateChecklistQuery = $pdo->prepare("UPDATE tasks SET checklist = :cheklist WHERE id = :taskId");
        $checklistJson = json_encode($editedChecklist);
        $updateChecklistQuery->execute([':taskId' => $idtask, ':cheklist' => $checklistJson]);
        //Перезаписать чеклист
    }

// Удалены ли загруженные файлы
    $uploadedFilesQuery = $pdo->prepare("SELECT file_id, file_name, file_size, file_path, is_deleted, cloud FROM uploads WHERE comment_type = 'task' AND comment_id = :taskId AND is_deleted = 0");
    $uploadedFilesQuery->execute([':taskId' => $idtask]);
    $uploadedFiles = $uploadedFilesQuery->fetchAll(PDO::FETCH_ASSOC);
    $oldUploadsUnsafe = json_decode($_POST['oldUploads']);
    $oldUploads = [];
    foreach ($oldUploadsUnsafe as $olUploadId) {
        $oldUploads[] = filter_var($olUploadId, FILTER_SANITIZE_NUMBER_INT);
    }
    foreach ($uploadedFiles as $file) {
        if (!in_array($file['file_id'], $oldUploads)) {
            removeFile($file['file_id']);
        }
    }
    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $newGoogleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $newGoogleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }

// Загрузка новых файлов
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
    $newDropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $newDropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    if (count($_FILES) > 0) {
        uploadAttachedFiles('task', $idtask);
    }
    if (count($newGoogleFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3 || $isPremiumUsed)) {
        addGoogleFiles('task', $idtask, $newGoogleFiles);
        $usePremiumCloud = true;
    }
    if (count($newDropboxFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3 || $isPremiumUsed)) {
        addDropboxFiles('task', $idtask, $newDropboxFiles);
        $usePremiumCloud = true;
    }

    $status = 'new';
    if (isset($_POST['startdate']) && ($tariff != 0 || $tryPremiumLimits['task'] < 3 || $isPremiumUsed)) {
        $newStartDate = strtotime(filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS));
        if ($newStartDate != $taskData['datecreate'] && $taskData['status'] == 'planned') {
            if (date('Y-m-d', $dateCreate) > date('Y-m-d') && date('Y-m-d', $dateCreate) <= date('Y-m-d', $newDatedone)) {
                $updateStartDateQuery = $pdo->prepare("UPDATE tasks SET datecreate = :startDate WHERE id = :taskId");
                $updateStartDateQuery->execute([':taskId' =>$idtask, ':startDate' => $newStartDate]);
                $usePremiumTask = true;
            }
        }
    }

    // Обновление лимитов бесплатного премиума
    if ($tariff == 0) {
        updateFreePremiumLimits($idc, 'edit');
        if ($usePremiumTask && !$isPremiumUsed) {
            updateFreePremiumLimits($idc, 'task');
            $updateTaskQuery = $pdo->prepare("UPDATE tasks SET with_premium = 1 WHERE id = :taskId");
            $updateTaskQuery->execute([':taskId' => $idtask]);
        }
        if ($usePremiumCloud) {
            updateFreePremiumLimits($idc, 'cloud');
        }
    }

    if ($status != 'planned') {
        resetViewStatus($idtask);
    }

    $result['taskId'] = $idtask;
    echo json_encode($result);
}
