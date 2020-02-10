<?php

require_once __ROOT__ . '/engine/backend/functions/task-functions.php';
require_once __ROOT__ . '/engine/backend/classes/Task.php';

global $idc;
global $roleu;
global $tariff;

$tryPremiumLimits = getFreePremiumLimits($idc);
// Возможность премиум: 1 - премиум-тариф, 0 - бесплатный тариф, есть бесплатные попытки,
// -1 - бесплатный тариф, нет бесплатных попыток
if ($tariff == 1) {
    $cloudPremiumType = 1;
    $taskPremiumType = 1;
    $editPremiumType = 1;
} else {
    // Прикрепление из облака
    if ($tryPremiumLimits['cloud'] < 3) {
        $cloudPremiumType = 0;
    } else {
        $cloudPremiumType = -1;
    }
    // Дополнительные функции задачи
    if ($tryPremiumLimits['task'] < 3) {
        $taskPremiumType = 0;
    } else {
        $taskPremiumType = -1;
    }
    // Редактирование задачи
    if ($tryPremiumLimits['edit'] < 3) {
        $editPremiumType = 0;
    } else {
        $editPremiumType = -1;
    }
}

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

    if ((in_array($taskStatus, ['done', 'canceled']) && $_POST['module'] != 'returnToWork') && (!isset($_POST['module']) || $_POST['module'] != 'cancelRepeat')) {
        exit;
    }
    $repeatType = $task->get('repeat_type');

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

        $task->sendOnReview($report, $cloudFiles, $cloudPremiumType);
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
        $task->workReturn($datePostpone, $text, $cloudFiles, $cloudPremiumType);

    }

// Завершение задачи - есть метод в классе
    if ($_POST['module'] == 'workdone') {
        if (!$isManager) {
            exit;
        }
        $unfinishedSubTasks = $task->checkSubTasksForFinish();
        if ($unfinishedSubTasks['status']) {
            $task->workDone();
            if ($idTaskManager == 1 && $roleu == 'ceo') {
                $count = DBOnce('COUNT(DISTINCT id)', 'tasks', "status IN ('done', 'canceled') AND manager = 1 AND worker = " . $id);
                if ($count > 1) {
                    $_SESSION['showRateModal'] = true;
                }
            }
            $subTasks = $task->get('subTasks');
            if (count($subTasks) > 0) {
                foreach ($subTasks as $subTask) {
                    if ($subTask->get('repeat_type') > 0) {
                        Task::cancelRepeat($subTask->get('id'));
                    }
                }
            }
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
            if ($idTaskManager == 1 && $roleu == 'ceo') {
                $count = DBOnce('COUNT(DISTINCT id)', 'tasks', "status IN ('done', 'canceled') AND manager = 1 AND worker = " . $id);
                if ($count > 1) {
                    $_SESSION['showRateModal'] = true;
                }
            }
            $subTasks = $task->get('subTasks');
            if (count($subTasks) > 0) {
                foreach ($subTasks as $subTask) {
                    if ($subTask->get('repeat_type') > 0) {
                        Task::cancelRepeat($subTask->get('id'));
                    }
                }
            }
        }
        echo json_encode($unfinishedSubTasks);
    }

// Отмена повторения задачи
    if ($_POST['module'] == 'cancelRepeat') {
        $result = [
            'status' => true,
        ];
        if ($task->get('repeat_type') > 0) {
            Task::cancelRepeat($idtask);
            echo json_encode($result);
        }
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
        if ($newWorker != $task->get('worker') && !in_array($newWorker, $newCoworkers)) {
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
        $checkListRow = filter_var($_POST['checklistRow'], FILTER_SANITIZE_STRING);
        $checkListRowStatus = $task->checkRowInCheckList($checkListRow, $id);
        if ($checkListRowStatus == -1) {
            exit;
        } else {
            echo $checkListRowStatus;
        }
    }


// осстановление завершенной или отмененной задачи

    if ($_POST['module'] == 'returnToWork') {
        if (!$isManager) {
            exit;
        }
        $task->returnToWork();
    }
// Редактирование задачи

    if ($_POST['module'] == 'editTask' && $isManager) {
        //exit;
        $isTaskWithPremium = $task->get('with_premium');
        $isPremiumUsed = false;
        $isEditUsed = false;
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
        if ($editPremiumType < 0) {
            exit;
        }
        require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

// Изменение названия и описания
        $newName = trim($_POST['name']);
        $newName = filter_var($newName, FILTER_SANITIZE_SPECIAL_CHARS);
        $newDescription = trim($_POST['description']);
        $newDescription = encodeTextTags($newDescription);
        $newDescription = filter_var($newDescription, FILTER_SANITIZE_SPECIAL_CHARS);
        $isNameAndDescriptionEdited = $task->updateTaskNameAndDescription($newName, $newDescription);
        if ($isNameAndDescriptionEdited) {
            $isEditUsed = true;
        }

// Изменился ли дедлайн
        $newDatedone = strtotime(filter_var($_POST['datedone'], FILTER_SANITIZE_SPECIAL_CHARS));
        $nowDate = strtotime('midnight');
        if ($newDatedone) {
            $isDatedoneEdited = $task->updateDatedone($newDatedone);
            if ($isDatedoneEdited) {
                $isEditUsed = true;
            }
        }

// Изменился ли ответственный
        $newWorker = filter_var($_POST['worker'], FILTER_SANITIZE_NUMBER_INT);
        $isWorkerChanged = $task->changeWorker($newWorker);

// Изменились ли соисполнители
        $unsafeCoworkers = json_decode($_POST['coworkers']);
        $newCoworkers = [];
        foreach ($unsafeCoworkers as $c) {
            $newCoworkers[] = filter_var($c, FILTER_SANITIZE_NUMBER_INT);
        }
        $isCoworkersChanged = $task->changeCoworkers($newCoworkers);
        if (($isWorkerChanged || $isCoworkersChanged)) {
            $isEditUsed = true;
            if ($taskStatus != 'planned') {
                resetViewStatus($idtask);
            }
        }

// Редактирование надзадачи
        $isParentTaskChanged = false;
        $newParentTask = filter_var($_POST['parentTask'], FILTER_SANITIZE_NUMBER_INT);
        if (is_null($parentTask) && $newParentTask != 0 && ($taskPremiumType >= 0 || $isTaskWithPremium)) {
            // Назначение надзадачи
            $isParentTaskChanged = $task->addParentTask($newParentTask);
        } elseif (!is_null($parentTask) && $newParentTask == 0) {
            // Снятие надзадачи
            $isParentTaskChanged = $task->removeParentTask();
        } elseif (!is_null($parentTask) && $newParentTask != 0) {
            // Смена надзадачи
            $isParentTaskChanged = $task->addParentTask($newParentTask);
        }
        if ($isParentTaskChanged) {
            $isEditUsed = true;
            $usePremiumTask = true;
        }

// Перезапись чеклиста
        $newCheckList = [];
        if (isset($_POST['checklist'])) {
            $newCheckList = Task::createSanitizedCheckList($_POST['checklist']);
        }
        $oldChecklistRows = [];
        if (isset($_POST['oldChecklistRows'])) {
            $unsafeOldChecklistRows = json_decode($_POST['oldChecklistRows']);
            foreach ($unsafeOldChecklistRows as $row) {
                $oldChecklistRows[] = filter_var($row, FILTER_SANITIZE_NUMBER_INT);
            }
        }
        if ($taskPremiumType >= 0 || $isTaskWithPremium) {
            $isCheckListChanged = $task->updateCheckList($newCheckList, $oldChecklistRows);
        }

        if ($isCheckListChanged) {
            $isEditUsed = true;
            $usePremiumTask = true;
        }

// Удалены ли загруженные файлы
        $isFilesChanged = false;
        $uploadedFiles = $task->get('files');
        $oldUploadsUnsafe = json_decode($_POST['oldUploads']);
        $oldUploads = [];
        foreach ($oldUploadsUnsafe as $oldUploadId) {
            $oldUploads[] = filter_var($oldUploadId, FILTER_SANITIZE_NUMBER_INT);
        }
        $isCloudAlreadyUsed = false;
        foreach ($uploadedFiles as $file) {
            if ($file['cloud']) {
                $isCloudAlreadyUsed = true;
            }
            if (!in_array($file['file_id'], $oldUploads)) {
                removeFile($file['file_id']);
                $isFilesChanged = true;
            }
        }
        if ($isFilesChanged) {
            $isEditUsed = true;
        }
        // Загрузка новых файлов
        $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
        $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
        $cloudFiles = sanitizeCloudUploads($unsafeGoogleFiles, $unsafeDropboxFiles);
        $task->attachDeviceFilesToTask();
        if ($isCloudAlreadyUsed) {
            $task->attachCloudFilesToTask($cloudFiles, 1);
        } else {
            $task->attachCloudFilesToTask($cloudFiles, $cloudPremiumType);
        }

// Изменение даты старта отложенной задачи
        if (isset($_POST['startdate']) && ($editPremiumType >= 0 && ($isTaskWithPremium || $taskPremiumType >= 0))) {
            $newStartDate = strtotime(filter_var($_POST['startdate'], FILTER_SANITIZE_SPECIAL_CHARS));
            $isStartDateChanged = $task->changeStartDate($newStartDate);
            if ($isStartDateChanged) {
                $usePremiumTask = true;
                $isEditUsed = true;
            }
        }

// Обновление лимитов бесплатного премиума
        if ($tariff == 0) {
            if ($isEditUsed) {
                updateFreePremiumLimits($idc, 'edit');
            }
            if ($usePremiumTask && !$isTaskWithPremium) {
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
    if (isset($_POST['checklist'])) {
        $checklist = Task::createSanitizedCheckList($_POST['checklist']);
    } else {
        $checklist = [];
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
    $description = encodeTextTags($description);
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
    $repeatType = filter_var($_POST['repeatType'], FILTER_SANITIZE_NUMBER_INT);
    if ($repeatType < 0 || $repeatType > 7 || $worker != $id || count($coworkers) > 0) {
        $repeatType = 0;
    }
    $task = Task::createTask($name, $description, $startDate, $id, $worker, $coworkers, $datedone, $checklist, $parentTask, $taskPremiumType, $repeatType);
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
