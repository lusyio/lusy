<?php

require_once __ROOT__ . '/engine/backend/functions/task-functions.php';
require_once __ROOT__ . '/engine/backend/classes/Task.php';

global $idc;
global $roleu;
global $tariff;

$tryPremiumLimits = getFreePremiumLimits($idc);
$isManager = false;
$isWorker = false;
$usePremiumTask = false;
$usePremiumCloud = false;

if ($roleu == 'ceo') {
    $isManager = true;
}

if (isset($_POST['it'])) {
    $idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);

    $task = new Task($idtask);

    // Выход если задача чужой компании
    if ($task->get('idcompany') != $idc) {
        exit;
    }

    $taskName = $task->get('name');
    $taskDescription = $task->get('description');
    $taskAuthorId = $task->get('author');
    $idTaskManager = $task->get('manager');
    $idTaskWorker = $task->get('worker');
    $startDate = $task->get('datecreate');
    $taskDatedone = $task->get('datedone');
    $parentTask = $task->get('parent_task');
    $checklist = json_decode($task->get('checklist'), true);
    if ($id == $idTaskManager || $idTaskManager == 1) {
        $isManager = true;
    }
    $coworkers = $task->get('coworkers');
    if ($id == $idTaskWorker || in_array($id, $coworkers)) {
        $isWorker = true;
    }
    $taskStatus = $task->get('status');
    if (in_array($taskStatus, ['done', 'canceled'])) {
        exit;
    }


// Перенос старта задачи - есть метод в классе
    if ($_POST['module'] == 'changeStartDate') {
        if (!$isManager) {
            exit;
        }
        $newDate = strtotime(filter_var($_POST['startDate'], FILTER_SANITIZE_NUMBER_INT));
        if ($task->get('status') != 'planned' || !$task->hasEditAccess) {
            exit;
        }
        $task->changeStartDate($newDate);
    }

// Перенос срока по инициативе менеджера - есть метод в классе
    if ($_POST['module'] == 'sendDate') {
        $newDate = strtotime(filter_var($_POST['sendDate'], FILTER_SANITIZE_NUMBER_INT));
        if ($newDate == $task->get('datedone') || !$task->hasEditAccess) {
            exit;
        }
        $task->sendDate($newDate);
    }

//Запрос на перенос срока - есть метод в классе
    if ($_POST['module'] == 'sendpostpone') {
        if (!$isWorker) {
            exit;
        }
        $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $datePostpone = strtotime(filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS));
        $task->sendPostpone($datePostpone, $text);
    }

// одобрение запроса на перенос срока - есть метод в классе
    if ($_POST['module'] == 'confirmDate') {
        if (!$isManager) {
            exit;
        }
        $task->confirmDate();
    }

// отклонение запроса на перенос срока - есть метод в классе
    if ($_POST['module'] == 'cancelDate') {
        if (!$isManager) {
            exit;
        }
        $task->cancelDate();
    }

// Отправка на рассмотрение - есть метод в классе
    if ($_POST['module'] == 'sendonreview') {
        if (!$isWorker) {
            exit;
        }
        $report = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);

        //Обработка прикрепленных из облака файлов
        $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
        $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
        $cloudFiles = sanitizeCloudUploads($unsafeGoogleFiles, $unsafeDropboxFiles);

        // Возможность прикрепления файлов: 1 - премиум-тариф, 0 - бесплатный тариф, есть бесплатные попытки,
        // -1 - бесплатный тариф, нет бесплатных попыток
        if ($tariff == 1) {
            $premiumType = 1;
        } elseif ($tryPremiumLimits['cloud'] < 3) {
            $premiumType = 0;
        } else {
            $premiumType = -1;
        }

        $task->sendOnReview($report, $cloudFiles, $premiumType);

    }

// Отклонение после рассмотрения задачи - есть метод в классе
    if ($_POST['module'] == 'workreturn') {
        if (!$isManager) {
            exit;
        }
        $datePostpone = strtotime(filter_var($_POST['datepostpone'], FILTER_SANITIZE_SPECIAL_CHARS));
        $text = filter_var($_POST['text'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        //Обработка прикрепленных из облака файлов
        $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
        $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);

        $cloudFiles = sanitizeCloudUploads($unsafeGoogleFiles, $unsafeDropboxFiles);

        // Возможность прикрепления файлов: 1 - премиум-тариф, 0 - бесплатный тариф, есть бесплатные попытки,
        // -1 - бесплатный тариф, нет бесплатных попыток
        if ($tariff == 1) {
            $premiumType = 1;
        } elseif ($tryPremiumLimits['cloud'] < 3) {
            $premiumType = 0;
        } else {
            $premiumType = -1;
        }

        $task->workReturn($datePostpone, $report, $cloudFiles, $premiumType);

    }

// Завершение задачи - есть метод в классе
    if ($_POST['module'] == 'workdone') {
        if (!$isManager) {
            exit;
        }
        $unfinishedSubTasks = $task->checkSubTasksForFinish();
        if ($unfinishedSubTasks['status']) {
            $task->workDone();
        }
        echo json_encode($unfinishedSubTasks);
    }

// Отмена задачи - есть метод в классе
    if ($_POST['module'] == 'cancelTask') {
        if (!$isManager) {
            exit;
        }
        $unfinishedSubTasks = $task->checkSubTasksForFinish();
        if ($unfinishedSubTasks['status']) {
            $task->cancelTask();
        }
        echo json_encode($unfinishedSubTasks);
    }

// Изменение исполнителя и соисполнителей - есть метод в классе
    if ($_POST['module'] == 'addCoworker') {
        if (!$task->hasEditAccess) {
            exit;
        }
        $unsafeCoworkers = json_decode($_POST['coworkers']);
        $newCoworkers = [];
        $newWorker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);

        foreach ($unsafeCoworkers as $c) {
            if ($c == $newWorker) {
                continue;
            }
            $newCoworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
        }
        $isCoworkersChanged = $task->changeCoworkers($newCoworkers);
        $isWorkerChanged = false;
        if ($newWorker != $task->get('worker') && $newWorker != $task->get('manager') && !in_array($newWorker, $newCoworkers)) {
            $isWorkerChanged = $task->changeWorker($newWorker);
        }
        if ($task->get('status') != 'planned' && ($isCoworkersChanged || $isWorkerChanged)) {
            resetViewStatus($idtask);
        }
    }

// Отметка в чеклисте - есть метод в классе
    if ($_POST['module'] == 'checklist') {
        if (!$isManager && !$isWorker && !isset($_POST['checklistRow'])) {
            exit;
        }
        $checklistRow = filter_var($_POST['checklistRow'], FILTER_SANITIZE_STRING);
        $checkListRowStatus = $task->updateCheckList($checklistRow, $id);
        if ($checkListRowStatus == -1) {
            exit;
        } else {
            echo $checkListRowStatus;
        }
    }

// *********************** //
//  TODO Редактирование задачи  //
// *********************** //
    if ($_POST['module'] == 'editTask' && $isManager) {
        //exit;
        $isPremiumUsed = false;
        $result = [
            'taskId' => '',
            'error' => '',
        ];
        // Не редактировать если задача от системного пользователя
        if ($idTaskManager == 1) {
            exit;
        }
        // Не редактировать если задача отменена или завершена
        if (in_array($taskStatus, ['done', 'canceled'])) {
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
        if (($newName != $taskName) || ($newDescription != $taskDescription)) {
            $updateNameAndDescriptionQuery = $pdo->prepare("UPDATE tasks SET name = :name, description = :description WHERE id = :taskId");
            $updateNameAndDescriptionQuery->execute([':taskId' => $idtask, ':name' => $newName, ':description' => $newDescription]);
        }

        // Изменился ли дедлайн
        $newDatedone = strtotime(filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS));
        $nowDate = strtotime('midnight');
        if ($newDatedone != $taskDatedone && $newDatedone >= $nowDate) {
            $updateDatedoneQuery = $pdo->prepare("UPDATE tasks SET datedone = :datedone, status = :status WHERE id = :taskId");
            $updateDatedoneQuery->execute([':taskId' => $idtask, ':status' => 'inwork', ':datedone' => $newDatedone]);
            if ($taskStatus != 'planned') {
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
        if ($newWorker != $idTaskWorker) {
            $changeWorkerQuery = $pdo->prepare('UPDATE tasks SET worker = :newWorker WHERE id = :taskId');
            $changeWorkerQuery->execute(array(':taskId' => $idtask, ':newWorker' => $newWorker));
            if ($taskStatus != 'planned') {
                addChangeExecutorsComments($idtask, 'newworker', $newWorker);
                addEvent('changeworker', $idtask, '', $idTaskWorker);
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
        if (is_null($parentTask) && $newParentTask != 0) {
            //Назначение надзадачи
            addSubTaskComment($newParentTask, $idtask);
            addNewSubTaskEvent($newParentTask, $idtask);
        }
        if (!is_null($parentTask) && $newParentTask == 0) {
            // Удаление надзадачи
        }
        if (!is_null($parentTask) && $newParentTask != 0) {
            // Смена надзадачи
        }
        $updateParentTaskQuery = $pdo->prepare("UPDATE tasks SET parent_task = :parentTask WHERE id = :taskId");
        if ($newParentTask != 0) {
            if (is_null($parentTask) || $newParentTask != $parentTask) {
                $updateParentTaskQuery->execute([':taskId' => $idtask, ':parentTask' => $newParentTask]);
                addSubTaskComment($newParentTask, $idtask);
                addNewSubTaskEvent($newParentTask, $idtask);
            }
        } else {
            if (!is_null($parentTask)) {
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

        if (isset($_POST['startdate']) && ($tariff != 0 || $tryPremiumLimits['task'] < 3 || $isPremiumUsed)) {
            $newStartDate = strtotime(filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS));
            if ($newStartDate != $startDate && $taskStatus == 'planned') {
                if (date('Y-m-d', $newStartDate) > date('Y-m-d') && date('Y-m-d', $newStartDate) <= date('Y-m-d', $newDatedone)) {
                    $updateStartDateQuery = $pdo->prepare("UPDATE tasks SET datecreate = :startDate WHERE id = :taskId");
                    $updateStartDateQuery->execute([':taskId' => $idtask, ':startDate' => $newStartDate]);
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

        if ($taskStatus != 'planned') {
            resetViewStatus($idtask);
        }

        $result['taskId'] = $idtask;
        echo json_encode($result);
    }
}

// создание новой задачи - есть метод в классе
if ($_POST['module'] == 'createTask') {
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
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
    $cloudFiles = sanitizeCloudUploads($unsafeGoogleFiles, $unsafeDropboxFiles);
    if (isset($_POST['manager'])) {
        $managerId = filter_var($_POST['manager'], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $managerId = $id;
    }
    $coworkers = array_unique($coworkers, SORT_NUMERIC);
    $name = trim($_POST['name']);
    $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
    $description = trim($_POST['description']);
    $description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
    $datedone = strtotime(filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS));
    $worker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
    foreach ($coworkers as $key => $coworker) {
        if ($coworker == $managerId || $coworker == $worker) {
            unset($coworkers[$key]);
        }
    }
    $startDate = strtotime(filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS));
    $parentTask = filter_var($_POST['parentTask'], FILTER_SANITIZE_NUMBER_INT);

    // Возможность создания премиум-задачи: 1 - премиум-тариф, 0 - бесплатный тариф, есть бесплатные попытки,
    // -1 - бесплатный тариф, нет бесплатных попыток
    if ($tariff == 1) {
        $taskPremiumType = 1;
    } elseif ($tryPremiumLimits['task'] < 3) {
        $taskPremiumType = 0;
    } else {
        $taskPremiumType = -1;
    }

    // Возможность прикрепления файлов: 1 - премиум-тариф, 0 - бесплатный тариф, есть бесплатные попытки,
    // -1 - бесплатный тариф, нет бесплатных попыток
    if ($tariff == 1) {
        $cloudPremiumType = 1;
    } elseif ($tryPremiumLimits['cloud'] < 3) {
        $cloudPremiumType = 0;
    } else {
        $cloudPremiumType = -1;
    }

    $task = Task::createTask($name, $description, $startDate, $id, $worker, $coworkers, $datedone, $parentTask, $checklist, $taskPremiumType);
    if ($task) {
        $task->attachDeviceFilesToTask();
        $task->attachCloudFilesToTask($cloudFiles, $cloudPremiumType);
        $result['taskId'] = $task->get('id');
    } else {
        $result['error'] = 'error';
    }
    echo json_encode($result);
    exit;
}
