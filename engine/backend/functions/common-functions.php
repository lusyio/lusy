<?php

/**
 * Загружает каждый файл из массива _FILES в upload/files/
 * и добавляет информацию о нем в бд в таблицу uploads
 * @param string $type Type to which the files is attached: 'task' or 'comment'
 * @param int $eventId Id of specified type event
 */
function uploadAttachedFiles($type, $eventId)
{
    global $pdo;
    global $idc;
    global $id;

    require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

    $providedStorageSpace = getProvidedStorageSpace();
    $companyTotalFilesSize = getCompanyFilesTotalSize();
    $availableSpace = $providedStorageSpace - $companyTotalFilesSize;
    $uploadFilesSize = 0;
    foreach ($_FILES as $file) {
        $uploadFilesSize += $file['size'];
    }
    if ($uploadFilesSize > $availableSpace) {
        return;
    }

    $maxFileSize = 500 * 1024 * 1024;
    $types = ['task', 'comment', 'conversation', 'chat'];
    if (!in_array($type, $types)) {
        return;
    }

    if ($type == 'comment') {
        global $idtask;
    } elseif ($type == 'conversation') {
        $idtask = 'm' . floor($eventId / 100);
    } elseif ($type == 'chat') {
        $idtask = 'c' . floor($eventId / 100);
    } else {
        $idtask = $eventId;
    }

    $dirName = 'upload/files/' . $idtask;
    if (!realpath($dirName)) {
        mkdir($dirName, 0777, true);
    }

    $sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type, company_id, is_deleted, author) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType, :companyId, :isDeleted, :author)');
    foreach ($_FILES as $file) {
        if ($file['size'] > $maxFileSize || $file['size'] == 0) {
            continue;
        }
        $fileName = basename($file['name']);
        $hashName = md5_file($file['tmp_name']);
        while (file_exists($dirName . '/' . $hashName)) {
            $hashName = md5($hashName);
        }
        $filePath = $dirName . '/' . $hashName;
        $sql->execute(array(':fileName' => $fileName, ':fileSize' => $file['size'], ':filePath' => $filePath, ':commentId' => $eventId, ':commentType' => $type, ':companyId' => $idc, ':isDeleted' => 0, ':author' => $id));
        move_uploaded_file($file['tmp_name'], $filePath);
    }
}

function addGoogleFiles($type, $eventId, $fileList)
{
    global $pdo;
    global $idc;
    global $id;

    require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

    $types = ['task', 'comment', 'conversation', 'chat'];
    if (!in_array($type, $types)) {
        return;
    }

    if ($type == 'comment') {
        global $idtask;
    } elseif ($type == 'conversation') {
        $idtask = 'm' . floor($eventId / 100);
    } else {
        $idtask = $eventId;
    }


    $sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type, company_id, is_deleted, author, cloud) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType, :companyId, :isDeleted, :author, :cloud)');
    foreach ($fileList as $file) {
        $sqlData = [
            ':fileName' => $file['name'],
            ':fileSize' => $file['size'],
            ':filePath' => $file['path'],
            ':commentId' => $eventId,
            ':commentType' => $type,
            ':companyId' => $idc,
            ':isDeleted' => 0,
            ':author' => $id,
            ':cloud' => 1,
            ];
        $sql->execute($sqlData);
    }
}

function addDropboxFiles($type, $eventId, $fileList)
{
    global $pdo;
    global $idc;
    global $id;

    require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

    $types = ['task', 'comment', 'conversation', 'chat'];
    if (!in_array($type, $types)) {
        return;
    }

    if ($type == 'comment') {
        global $idtask;
    } elseif ($type == 'conversation') {
        $idtask = 'm' . floor($eventId / 100);
    } else {
        $idtask = $eventId;
    }


    $sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type, company_id, is_deleted, author, cloud) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType, :companyId, :isDeleted, :author, :cloud)');
    foreach ($fileList as $file) {
        $sqlData = [
            ':fileName' => $file['name'],
            ':fileSize' => $file['size'],
            ':filePath' => $file['path'],
            ':commentId' => $eventId,
            ':commentType' => $type,
            ':companyId' => $idc,
            ':isDeleted' => 0,
            ':author' => $id,
            ':cloud' => 1,
            ];
        $sql->execute($sqlData);
    }
}

function authorizeComet($id)
{
    global $cometPdo;
    $hash = md5($id . 'salt-pepper');
    //Очищаем очередь личных сообщений с комет-сервера, т.к. они подгрузятся напрямую из базы
    $cometSql = $cometPdo->prepare("DELETE FROM users_messages WHERE id =:id");
    $cometSql->execute(array(':id' => $id));
    $cometSql = $cometPdo->prepare("INSERT INTO users_auth (id, hash )VALUES (:id, :hash)");
    $cometSql->execute(array(':id' => $id, ':hash' => $hash));
    return $hash;
}

function getCometTrackChannelName($companyId = null)
{
    global $idc;
    if (!is_null($companyId)){
        $idc = $companyId;
    }
    $saltIdc = md5('someSalt' . $idc);
    $channelName = 'track_online_' . $saltIdc;
    return $channelName;
}

function addEvent($action, $taskId, $comment, $recipientId = null)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $possibleActions = ['createtask', 'createplantask', 'viewtask', 'comment', 'overdue', 'review', 'postpone', 'confirmdate', 'canceldate',
        'senddate', 'workreturn', 'workdone', 'canceltask', 'changeworker', 'addcoworker', 'removecoworker', 'newuser', 'userwelcome',
        'newcompany', 'newachievement', 'edittask'];

    if (!in_array($action, $possibleActions)) {
        return false;
    }

    $executorsQuery = $pdo->prepare('SELECT worker, manager FROM tasks WHERE id = :taskId');
    $executorsQuery->execute(array(':taskId' => $taskId));
    $executors = $executorsQuery->fetch(PDO::FETCH_ASSOC);
    $taskManager = $executors['manager'];
    $taskWorker = $executors['worker'];
    $isSelfTask = ($taskManager == $taskWorker);

    if ($action == 'createtask') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        if(!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));
            //sendTaskWorkerEmailNotification($taskId, 'createtask');
            addMailToQueue('sendTaskWorkerEmailNotification', [$taskId, 'createtask'], $recipientId, $workerEventId);
        }
    }

    if ($action == 'createplantask') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $addEventQuery->execute($eventDataForAuthor);
    }

    if ($action == 'viewtask' && !$isSelfTask) {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime)');
        $eventData = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
        ];
        $addEventQuery->execute($eventData);
        $eventId = $pdo->lastInsertId();
        $pushData = [
            'type' => 'task',
            'eventId' => $eventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));
    }

    if ($action == 'canceltask') {
        $eventDataForManager = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskManager,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $addEventQuery->execute($eventDataForManager);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
        }
    }

    if ($action == 'overdue') {
        $idc = DBOnce('idcompany', 'tasks', 'id=' . (int) $taskId);
        $eventDataForManager = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskManager,
            ':companyId' => $idc,
            ':datetime' => time(),
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime)');
        $addEventQuery->execute($eventDataForManager);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();
            //sendTaskWorkerEmailNotification($taskId, 'overdue');
            addMailToQueue('sendTaskWorkerEmailNotification', [$taskId, 'overdue'], $taskWorker, $workerEventId);

        }
    }

    if ($action == 'review') {
        $eventDataForManager = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $taskWorker,
            ':recipientId' => $taskManager,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
            ':comment' => $comment,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
            ':comment' => $comment,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus, :comment)');
        $addEventQuery->execute($eventDataForWorker);
        $addEventQuery->execute($eventDataForManager);
        $managerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $managerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $taskManager, ':type' => json_encode($pushData)));

        //sendTaskManagerEmailNotification($taskId, 'review');
        addMailToQueue('sendTaskManagerEmailNotification', [$taskId, 'review'], $taskManager, $managerEventId);
    }

    if ($action == 'workreturn') {
        $eventDataForManager = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskManager,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $taskManager,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $addEventQuery->execute($eventDataForManager);
        $addEventQuery->execute($eventDataForWorker);
        $workerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $workerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
    }

    if ($action == 'workdone') {
        if (!isset($idc)) {
            $idc = DBOnce('idcompany', 'tasks', 'id=' . (int) $taskId);
        }
        $eventDataForManager = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskManager,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $addEventQuery->execute($eventDataForManager);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
        }
    }

    if ($action == 'postpone') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus, :comment)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
            ':comment' => $comment,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
            ':comment' => $comment,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        $addEventQuery->execute($eventDataForWorker);
        $managerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $managerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));

        //sendTaskManagerEmailNotification($taskId, 'postpone');
        addMailToQueue('sendTaskManagerEmailNotification', [$taskId, 'postpone'], $recipientId, $managerEventId);
    }

    if ($action == 'confirmdate' || $action == 'canceldate') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        $addEventQuery->execute($eventDataForWorker);
        $workerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $workerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
    }

    if ($action == 'changeworker') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus, :comment)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
            ':comment' => '',
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
            ':comment' => '',
        ];
        $addEventQuery->execute($eventDataForAuthor);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));
        }
        $eventDataForWorker = [
            ':action' => 'createtask',
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
            ':comment' => $comment,
        ];
        if($taskWorker != $id) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
            //sendTaskWorkerEmailNotification($taskId, 'createtask');
            addMailToQueue('sendTaskWorkerEmailNotification', [$taskId, 'createtask'], $taskWorker, $workerEventId);
        }

    }

    if ($action == 'senddate') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
        }
    }

    if ($action == 'addcoworker' || $action == 'removecoworker') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            'comment' => $recipientId,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
            'comment' => '',
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        $addEventQuery->execute($eventDataForWorker);
        $workerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $workerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));
    }

    if ($action == 'newuser') {
        $ceoId = DBOnce('id', 'users', 'idcompany=' . $idc . ' AND role="ceo"');
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
        $datetime = time();
        $eventData = [
            ':action' => $action,
            ':taskId' => 0,
            ':recipientId' => $ceoId,
            ':authorId' => 1,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
        ];
        $addEventQuery->execute($eventData);
    }

    if ($action == 'userwelcome') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
        $datetime = time();
        $eventData = [
            ':action' => $action,
            ':taskId' => 0,
            ':recipientId' => $recipientId,
            ':authorId' => 1,
            ':companyId' => $idc,
            ':datetime' => time() - 10,
            ':comment' => $comment,
        ];
        $addEventQuery->execute($eventData);
    }

    if ($action == 'newcompany') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime)');
        $datetime = time();
        $eventData = [
            ':action' => $action,
            ':taskId' => 0,
            ':recipientId' => $recipientId,
            ':authorId' => 1,
            ':companyId' => $idc,
            ':datetime' => time() - 10,
        ];
        $addEventQuery->execute($eventData);
    }

    if ($action == 'newachievement') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventData = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventData);
        $eventId = $pdo->lastInsertId();
        $pushData = [
            'type' => 'task',
            'eventId' => $eventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));

        //Сообщение в чат
        $message = getDisplayUserName($id) . ' получил новое достижение - ' . $GLOBALS['_' . $comment] . '!';
        $addMessageToChatQuery = $pdo->prepare("INSERT INTO chat (text, author_id, datetime, company_id) VALUES (:message, :authorId, :datetime, :companyId)");
        $addMessageToChatQuery->execute(array(':message' => $message, ':authorId' => 1, ':datetime' => time(), ':companyId' => $idc));
        $messageId = $pdo->lastInsertId();

        $cometSql = $cometPdo->prepare("INSERT INTO pipes_messages (name, event, message) VALUES (:channelName, 'newChat', :jsonMesData)");
        $mesData = [
            'messageId' => $messageId,
        ];
        $jsonMesData = json_encode($mesData);
        $cometSql->execute(array(':channelName' => getCometTrackChannelName($idc), ':jsonMesData' => $jsonMesData));

        //sendAchievementEmailNotification($id, $comment);
        addMailToQueue('sendAchievementEmailNotification', [$id, $comment], $id, $eventId);
    }

    if ($action == 'edittask') {
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForAuthor);
        if (!$isSelfTask) {
            $addEventQuery->execute($eventDataForWorker);
            $workerEventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'task',
                'eventId' => $workerEventId,
            ];
            $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
            $sendToCometQuery->execute(array(':id' => $taskWorker, ':type' => json_encode($pushData)));
        }
    }
}

function addMassEvent($action, $taskId, $comment)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $possibleActions = ['comment'];

    if (!in_array($action, $possibleActions)) {
        return;
    }
    $coworkersQuery = $pdo->prepare('SELECT worker_id FROM task_coworkers WHERE task_id=:taskId');
    $coworkersQuery->execute(array(':taskId' => $taskId));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN, 0);
    $managersQuery = $pdo->prepare('SELECT author, manager FROM tasks WHERE id=:taskId');
    $managersQuery->execute(array(':taskId' => $taskId));
    $managers = $managersQuery->fetch();
    var_dump($coworkers);
    var_dump($managers);
    if (count($coworkers) > 0) {
        $recipients = array_merge($coworkers, $managers);
    } else {
        $recipients = $managers;
    }
    $recipients = array_unique($recipients);
    if (($key = array_search($id, $recipients)) !== false) {
        unset($recipients[$key]);
    }

    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $datetime = time();

    $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
    $type = 'comment';

    foreach ($recipients as $recipient) {
        $eventData = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':recipientId' => $recipient,
            ':authorId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':comment' => $comment,
        ];

        $addEventQuery->execute($eventData);
        $eventId = $pdo->lastInsertId();
        $pushData = [
            'type' => $type,
            'eventId' => $eventId,
        ];
        $sendToCometQuery->execute(array(':id' => $recipient, ':type' => json_encode($pushData)));
    }
}

function addMassSystemEvent($action, $comment = '', $companyId = '')
{
    global $id;
    global $pdo;
    global $cometPdo;
    if ($companyId == '') {
        global $idc;
    } else {
        $idc = $companyId;
    }

    $possibleActions = ['newuser', 'newcompany'];

    if (!in_array($action, $possibleActions)) {
        return;
    }

    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $datetime = time();
    $eventData = [
        ':action' => $action,
        ':taskId' => 0,
        ':recipientId' => '',
        ':authorId' => '',
        ':companyId' => $idc,
        ':datetime' => time(),
        ':comment' => $comment,
    ];
    $addEventQuery->execute($eventData);

    $companyUsersQuery = $pdo->prepare('SELECT id FROM users WHERE idcompany = :companyId');
    $companyUsersQuery->execute(array(':companyId' => $idc));

}

function getAvatarLink($userId)
{
    if ($userId == 1) {
        return 'upload/avatar/0/1-alter.jpg';
    } else {
        global $idc;
    }
    if ($idc == 1) {
        $idc = DBOnce('idcompany', 'users', 'id = ' . $userId);
    }
    $avatarPath = 'upload/avatar/' . $idc . '/' . $userId . '.jpg';
    $alterAvatarPath = 'upload/avatar/' . $idc . '/' . $userId . '-alter.jpg';
    if (file_exists($avatarPath)) {
        return $avatarPath;
    } elseif (file_exists($alterAvatarPath)) {
        return $alterAvatarPath;
    } else {
        createAlterAvatar($userId);
        return $alterAvatarPath;
    }
}

/**Генерирует аватарку пользователя из первых букв имени и фамилии в формате .jpg
 * и сохраняет в директории upload/avatar с именем $userId-alter.jpg
 * @param $userId int ID пользователя
 */
function createAlterAvatar($userId)
{
    if ($userId == 1) {
        return;
    } else {
        global $idc;
    }

    // Получаем из БД имя и фамилию пользователя, обрезаем до одной  буквы если пустые - заменяем точками
    $userName = trim(DBOnce('name', 'users', 'id=' . $userId));
    $userSurname = trim(DBOnce('surname', 'users', 'id=' . $userId));
    if ($userName == '') {
        $userName = '.';
    }
    if ($userSurname == '') {
        $userSurname = '.';
    }
    $letters = mb_strtoupper(' ' . mb_substr($userName, 0, 1) . mb_substr($userSurname, 0, 1) . ' ');

    // Размеры аватарки, шрифта и файл шрифта
    $imageHeight = 190;
    $imageWidth = 190;
    $textSize = 64;
    $fontFile = realpath(__ROOT__ . '/engine/backend/fonts/Roboto-Regular.ttf');

    // Создаем изображение со сглаживанием
    $im = @imagecreatetruecolor($imageWidth, $imageHeight);
    imageantialias($im, true);

    // Набор фоновых цветов для случайного выбора
    $colors = [
        [56, 192, 208], [0, 48, 128], [69, 176, 230], [61, 136, 242], [230, 69, 69], [243, 151, 24], [71, 204, 193], [24, 184, 152], [0, 83, 156], [232, 24, 32], [184, 193, 217], [24, 184, 152], [168, 200, 232], [86, 191, 104], [143, 152, 79], [145, 97, 243],
    ];
    $colorSet = array_rand($colors);
    $backgroundColor = imagecolorallocate($im, $colors[$colorSet][0], $colors[$colorSet][1], $colors[$colorSet][2]);
    imagefill($im, 0, 0, $backgroundColor);

    //Измеряем ширину букв для центровки и наносим текст в полученные координаты
    $textCartesians = imagettfbbox($textSize, 0, $fontFile, $letters);
    $lettersWidth = abs($textCartesians[0] - $textCartesians[2]);
    $startX = ($imageHeight - $lettersWidth) / 2;
    $startY = 126; // подбирается вручную, в зависимости от размера шрифта
    $text_color = imagecolorallocate($im, 255, 255, 255);
    imageTtfText($im, $textSize, 0, $startX, $startY, $text_color, $fontFile, $letters);

    $avatarDir = 'upload/avatar/' . $idc . '/';
    if (!realpath($avatarDir)) {
        mkdir($avatarDir, 0777, true);
    }
    $avatarPath = 'upload/avatar/' . $idc . '/' . $userId . '-alter.jpg';
    imagejpeg($im, $avatarPath);
    imagedestroy($im);
}

function deleteAvatar($userId)
{
    global $idc;
    $avatarPath = 'upload/avatar/' . $idc . '/' . $userId . '.jpg';
    if (file_exists($avatarPath)) {
        unlink($avatarPath);
    }
}

function link_it($text)
{
    $text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a target=\"_blank\" href=\"$3\" >$3</a>", $text);
    $text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a target=\"_blank\" href=\"http://$3\" >$3</a>", $text);
    $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a target=\"_blank\" href=\"mailto:$2@$3\">$2@$3</a>", $text);
    return($text);
}

function getUserData($userId)
{
    global $idc;
    global $pdo;

    $userQuery = $pdo->prepare('SELECT id, login, email, phone, name, surname, idcompany, role, points, activity, register_date, social_networks, about, birthdate FROM users WHERE id = :userId AND idcompany = :companyId');
    $userQuery->execute(array(':userId' => $userId, ':companyId' => $idc));
    $userData = $userQuery->fetch(PDO::FETCH_ASSOC);
    if ($userData) {
        $socialNetworks = json_decode($userData['social_networks'], true);
        $userData['social'] = [];
        if (!is_null($socialNetworks)) {
            foreach ($socialNetworks as $network => $link) {
                $userData['social'][$network] = $link;
            }
        }
        $onlineUsers = getOnlineUsersList();
        $userData['online'] = false;
        if (in_array($userData['id'], $onlineUsers) || $userData['activity'] > time() - 180) {
            $userData['online'] = true;
        }
    }
    return $userData;
}

/**Преобразует UTC-время в локальное время пользователя (временная зона запрашивается из БД)
 * @param $utcTimestamp int Временная метка в формате unix-времени
 * @return int Временная метка в формате unix-времени
 * @throws Exception
 */
function localDateTime($utcTimestamp)
{
    global $idc;
    $givenDateTime = new DateTime();
    $givenDateTime->setTimestamp($utcTimestamp);

    // определяем тайм-зону компании
    $companyTimeZoneName = DBOnce('timezone', 'company', 'id=' . $idc);
    $companyTimeZone = new DateTimeZone($companyTimeZoneName);

    $userTimeOffsetFromGmt = timezone_offset_get($companyTimeZone, $givenDateTime);
    $offset = new DateInterval('PT' . abs($userTimeOffsetFromGmt) . 'S');
    if ($userTimeOffsetFromGmt > 0) {
        $givenDateTime->add($offset);
    } else {
        $givenDateTime->sub($offset);
    }

    return $givenDateTime->getTimestamp();
}

function setLastVisit()
{
    global $id;
    global $pdo;
    $addVisitQuery = $pdo->prepare('UPDATE users SET activity = :visitTime WHERE id = :userId');
    $addVisitQuery->execute(array(':userId' => $id, ':visitTime' => time()));
}

function getOnlineUsersList()
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;
    $onlineUsersQuery = $cometPdo->prepare('SELECT * FROM users_in_pipes WHERE name = :channelName');
    $onlineUsersQuery->execute(array(':channelName' => getCometTrackChannelName()));
    $onlineUsers = $onlineUsersQuery->fetchAll(PDO::FETCH_ASSOC);
    return array_column($onlineUsers, 'user_id');
}

function addCommentEvent($taskId, $commentId, $commentFromSystemUser = false, $delay = 0)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $taskStatusQuery = $pdo->prepare("SELECT status FROM tasks WHERE id=:taskId");
    $taskStatusQuery->execute(array(':taskId' => $taskId));
    $taskStatus = $taskStatusQuery->fetch(PDO::FETCH_COLUMN);
    if ($taskStatus == 'planned') {
        return;
    }
    $executorsQuery = $pdo->prepare('SELECT worker, manager FROM tasks WHERE id = :taskId');
    $executorsQuery->execute(array(':taskId' => $taskId));
    $executors = $executorsQuery->fetch(PDO::FETCH_ASSOC);
    $coworkersQuery = $pdo->prepare('SELECT worker_id FROM task_coworkers WHERE task_id = :taskId');
    $coworkersQuery->execute(array(':taskId' => $taskId));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN);

    $recipients = $coworkers;
    $recipients[] = $executors['manager'];
    $recipients[] = $executors['worker'];
    $recipients = array_unique($recipients);
    if ($commentFromSystemUser) {
        $id = 1;
    }
    if (($key = array_search($id, $recipients)) !== false) {
     unset($recipients[$key]);
    }


    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
    $eventIds = [];
    foreach ($recipients as $recipient) {
        if ($recipient != $id) {
            $eventData = [
                ':action' => 'comment',
                ':taskId' => $taskId,
                ':authorId' => $id,
                ':recipientId' => $recipient,
                ':companyId' => $idc,
                ':datetime' => time(),
                ':comment' => $commentId,
            ];
            if ($commentFromSystemUser) {
                $eventData[':datetime'] = time() + $delay;
            }
            $addEventQuery->execute($eventData);
            $eventId = $pdo->lastInsertId();
            $eventIds[$recipient] = $eventId;
            $pushData = [
                'type' => 'comment',
                'eventId' => $eventId,
            ];
            $sendToCometQuery->execute(array(':id' => $recipient, ':type' => json_encode($pushData)));
        }
    }

    //sendCommentEmailNotification($taskId, $id, $recipients, $commentId);
    foreach ($recipients as $key => $recipient) {
        addMailToQueue('sendCommentEmailNotification', [$taskId, $id, $recipient, $commentId], $recipient, $eventIds[$recipient]);

    }
}

function addNewSubTaskEvent($parentTaskId, $subTaskId)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $taskStatusQuery = $pdo->prepare("SELECT status FROM tasks WHERE id=:taskId");
    $taskStatusQuery->execute(array(':taskId' => $parentTaskId));
    $taskStatus = $taskStatusQuery->fetch(PDO::FETCH_COLUMN);
    if ($taskStatus == 'planned') {
        return;
    }
    $executorsQuery = $pdo->prepare('SELECT worker, manager FROM tasks WHERE id = :taskId');
    $executorsQuery->execute(array(':taskId' => $parentTaskId));
    $executors = $executorsQuery->fetch(PDO::FETCH_ASSOC);
    $coworkersQuery = $pdo->prepare('SELECT worker_id FROM task_coworkers WHERE task_id = :taskId');
    $coworkersQuery->execute(array(':taskId' => $parentTaskId));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN);

    $recipients = $coworkers;
    $recipients[] = $executors['manager'];
    $recipients[] = $executors['worker'];
    array_unique($recipients);
    if (($key = array_search($id, $recipients)) !== false) {
        unset($recipients[$key]);
    }


    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
    $eventIds = [];
    foreach ($recipients as $recipient) {
        if ($recipient != $id) {
            $eventData = [
                ':action' => 'newsubtask',
                ':taskId' => $parentTaskId,
                ':authorId' => $id,
                ':recipientId' => $recipient,
                ':companyId' => $idc,
                ':datetime' => time(),
                ':comment' => $subTaskId,
            ];
            $addEventQuery->execute($eventData);
            $eventId = $pdo->lastInsertId();
            $eventIds[$recipient] = $eventId;
            $pushData = [
                'type' => 'task',
                'eventId' => $eventId,
            ];
            $sendToCometQuery->execute(array(':id' => $recipient, ':type' => json_encode($pushData)));
        }
    }
}

function concatName($name, $surname)
{
    if ($name != '' && $surname != '') {
        $fullName = $name . ' ' . $surname;
    } else {
        if ($name != '') {
            $fullName = $name;
        } else {
            $fullName = $surname;
        }
    }
    return $fullName;
}

/**    Returns the offset from the origin timezone to the remote timezone, in seconds.
*    @param $remote_tz;
*    @param $origin_tz; If null the servers current timezone is used as the origin.
*    @return int;
*/
function get_timezone_offset($remote_tz, $origin_tz = null) {
    if($origin_tz === null) {
        if(!is_string($origin_tz = date_default_timezone_get())) {
            return false; // A UTC timestamp was returned -- bail out!
        }
    }
    $origin_dtz = new DateTimeZone($origin_tz);
    $remote_dtz = new DateTimeZone($remote_tz);
    $origin_dt = new DateTime("now", $origin_dtz);
    $remote_dt = new DateTime("now", $remote_dtz);
    $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
    return $offset;
}

function countTopsidebar()
{
    require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

    global $id;
    global $idc;
    $newTaskCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action NOT LIKE "comment"');
    $overdueCount = DBOnce('count(*)', 'tasks', '(worker='.$id.' or manager='.$id.') and status="overdue"');
    $newCommentCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action LIKE "comment"');
    $newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');
    $newChatCount = numberOfNewChatMessages();
    if ($idc == 1) {
        $newSupportCount = DBOnce('count(DISTINCT sender)', 'mail', 'recipient= 1 AND view_status=0');
    } else {
        $newSupportCount = 0;
    }
    $result = [
        'task' => $newTaskCount,
        'hot' => $overdueCount,
        'comment' => $newCommentCount,
        'mail' => $newMailCount + $newChatCount + $newSupportCount,
    ];
    return $result;
}

function sendTaskWorkerEmailNotification($taskId, $action)
{
    global $pdo;

    if ($action == 'createtask' || $action == 'overdue') {
        $userId = DBOnce('worker', 'tasks', 'id = ' . $taskId);
        $notifications = getNotificationSettings($userId);
    } else {
        return;
    }

    if (($action == 'createtask' && !$notifications['task_create']) || ($action == 'overdue' && !$notifications['task_overdue'])) {
        return;
    }

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE t.id = :taskId");
    $companyNameQuery->execute(array(':taskId' => $taskId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);

    $workerMailQuery = $pdo->prepare("SELECT u.email FROM tasks t LEFT JOIN users u ON t.worker = u.id WHERE t.id = :taskId");
    $workerMailQuery->execute(array(':taskId' => $taskId));
    $workerMail = $workerMailQuery->fetch(PDO::FETCH_COLUMN);

    $managerNameQuery = $pdo->prepare("SELECT u.name, u.surname FROM tasks t LEFT JOIN users u ON t.manager = u.id WHERE t.id = :taskId");
    $managerNameQuery->execute(array(':taskId' => $taskId));
    $managerNameResult = $managerNameQuery->fetch(PDO::FETCH_ASSOC);
    $managerName = trim($managerNameResult['name'] . ' ' . $managerNameResult['surname']);

    $taskName = DBOnce('name', 'tasks', 'id=' . $taskId);

    try {
        $mail->addAddress($workerMail);
        $mail->isHTML();

        $args = [
            'companyName' => $companyName,
            'taskId' => $taskId,
            'managerName' => $managerName,
            'taskName' => $taskName,
        ];

        if ($action == 'createtask') {
            $mail->Subject = "Вам назначена задача в Lusy.io";
            $mail->setMessageContent('new-task', $args);

        } elseif ($action == 'overdue') {
            $mail->Subject = "Просрочена задача, назначенная Вам в Lusy.io";
            $mail->setMessageContent('task-overdue', $args);
        }

        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendTaskManagerEmailNotification($taskId, $action)
{
    global $pdo;

    if ($action == 'review' || $action == 'postpone') {
        $userId = DBOnce('manager', 'tasks', 'id = ' . $taskId);
        $notifications = getNotificationSettings($userId);
    } else {
        return;
    }

    if (($action == 'review' && !$notifications['task_review']) || ($action == 'postpone' && !$notifications['task_postpone'])) {
        return;
    }

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE t.id = :taskId");
    $companyNameQuery->execute(array(':taskId' => $taskId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);

    $managerMailQuery = $pdo->prepare("SELECT u.email FROM tasks t LEFT JOIN users u ON t.manager = u.id WHERE t.id = :taskId");
    $managerMailQuery->execute(array(':taskId' => $taskId));
    $managerMail = $managerMailQuery->fetch(PDO::FETCH_COLUMN);

    $workerIdQuery = $pdo->prepare("SELECT t.worker FROM tasks t WHERE t.id = :taskId");
    $workerIdQuery->execute(array(':taskId' => $taskId));
    $workerId = $workerIdQuery->fetch(PDO::FETCH_COLUMN);
    $workerName = getDisplayUserName($workerId);
    $taskName = DBOnce('name', 'tasks', 'id=' . $taskId);

    try {
        $mail->addAddress($managerMail);
        $mail->isHTML();

        $args = [
            'companyName' => $companyName,
            'taskId' => $taskId,
            'workerName' => $workerName,
            'taskName' => $taskName,
        ];

        if ($action == 'review') {
            $report = DBOnce('comment', 'comments', "idtask=" . $taskId . " and status = 'report' order by `datetime` desc");
            $args['report'] = nl2br($report);
            $mail->Subject = "Вам отправлена на рассмотрение задача в Lusy.io";
            $mail->setMessageContent('task-review', $args);
        } elseif ($action == 'postpone') {
            $request = DBOnce('comment', 'comments', "idtask=" . $taskId . " and status like 'request%' order by `datetime` desc");
            $args['request'] = nl2br($request);
            $mail->Subject = "У Вас запрашивают перенос срока задачи в Lusy.io";
            $mail->setMessageContent('task-postpone', $args);
        }

        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendCommentEmailNotification($taskId, $authorId, $userIds, $commentId)
{
    global $id;
    global $pdo;
    if (!is_array($userIds)) {
        $userIds = [$userIds];
    }
    $usersToNotification = [];

    foreach ($userIds as $user) {
        $note = getNotificationSettings($user);
        if ($note['comment'] == 1 && $user != $id) {
            $usersToNotification[] = $user;
        }
        unset($user);
    }

    if (count($usersToNotification) == 0) {
        return;
    }

    $userMails = [];
    $userMailQuery = $pdo->prepare("SELECT email FROM users WHERE id = :userId");
    foreach ($usersToNotification as $user) {
        $userMailQuery->execute(array(':userId' => $user));
        $userMails[] = $userMailQuery->fetch(PDO::FETCH_COLUMN);
    }

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE t.id = :taskId");
    $companyNameQuery->execute(array(':taskId' => $taskId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);
    $authorName = getDisplayUserName($authorId);
    $taskName = DBOnce('name', 'tasks', 'id=' . $taskId);
    $comment = DBOnce('comment', 'comments', 'id=' . $commentId);
    $args = [
        'companyName' => $companyName,
        'taskId' => $taskId,
        'authorName' => $authorName,
        'taskName' => $taskName,
        'commentId' => $commentId,
        'commentText' => $comment,
    ];

    foreach ($userMails as $userMail) {
        try {
            $mail->addAddress($userMail);
        } catch (Exception $e) {
            continue;
        }
    }

    $mail->isHTML();
    $mail->Subject = "Новый комментарий к задаче в Lusy.io";
    $mail->setMessageContent('comment', $args);
    try {
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendMessageEmailNotification($userId, $authorId, $messageId)
{
    global $pdo;

    $notifications = getNotificationSettings($userId);
    if (!$notifications['message']) {
        return;
    }

    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM users u LEFT JOIN company c ON c.id = u.idcompany WHERE u.id = :userId");
    $companyNameQuery->execute(array(':userId' => $userId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);

    $authorNameQuery = $pdo->prepare("SELECT name, surname FROM users WHERE  id = :authorId");
    $authorNameQuery->execute(array(':authorId' => $authorId));
    $authorNameResult = $authorNameQuery->fetch(PDO::FETCH_ASSOC);
    $authorName = trim($authorNameResult['name'] . ' ' . $authorNameResult['surname']);

    $userMailQuery = $pdo->prepare("SELECT email FROM users WHERE id = :userId");
    $userMailQuery->execute(array(':userId' => $userId));
    $userMail = $userMailQuery->fetch(PDO::FETCH_COLUMN);

    $messageText = DBOnce('mes', 'mail', 'message_id = ' . $messageId);

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($userMail);
        $mail->isHTML();
        $mail->Subject = "Вам отправили личное сообщение в Lusy.io";
        $args = [
            'companyName' => $companyName,
            'authorName' => $authorName,
            'messageText' => $messageText,
        ];
        $mail->setMessageContent('message', $args);
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendAchievementEmailNotification($userId, $achievementName)
{
    global $pdo;

    $notifications = getNotificationSettings($userId);
    if (!$notifications['achievement']) {
        return;
    }

    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM users u LEFT JOIN company c ON c.id = u.idcompany WHERE u.id = :userId");
    $companyNameQuery->execute(array(':userId' => $userId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);


    $userMailQuery = $pdo->prepare("SELECT email FROM users WHERE id = :userId");
    $userMailQuery->execute(array(':userId' => $userId));
    $userMail = $userMailQuery->fetch(PDO::FETCH_COLUMN);

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($userMail);
        $mail->isHTML();
        $mail->Subject = "Вы получили новое достижение в Lusy.io";
        $args = [
            'companyName' => $companyName,
            'achievementName' => $GLOBALS['_' . $achievementName],
            'achievementText' => $GLOBALS['_' . $achievementName . '_text'],
        ];
        $mail->setMessageContent('achievement', $args);
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function getNotificationSettings($userId = null)
{
    if (is_null($userId)) {
        global $id;
        $userId = $id;
    }
    global $pdo;
    $notificationSettingsQuery = $pdo->prepare('SELECT user_id, task_create, task_overdue, comment, task_review, task_postpone, message, achievement, silence_start, silence_end, payment FROM user_notifications WHERE user_id = :userId');
    $notificationSettingsQuery->execute(array(':userId' => $userId));
    $result = $notificationSettingsQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function createInitTask($userId, $companyId, $forCeo = false)
{
    global $pdo;

    require_once __ROOT__ . '/engine/backend/functions/task-functions.php';
    require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

    $managerId = 1;
    $addTaskQuery = $pdo->prepare("INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view) VALUES (:name, :description, :dateCreate, :datedone, NULL, 'new', :author, :manager, :worker, :companyId, :description, '0') ");
    $addTaskCommentsQuery = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");
    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');

    // Создание задачи Заполнить профиль
    $name = 'Заполнить профиль';
    $description = '((parag))В настройках профиля ((link:https://s.lusy.io/settings/))https://s.lusy.io/settings/((link_end)) Вы можете указать контактную информацию, рассказать о себе или добавить ссылки на Ваши социальные сети!((parag_end))((parag))((breakline))((parag_end))((parag))((breakline))((parag_end))';
    $datedone = strtotime('midnight');
    $addTaskQueryData = [
        ':name' => $name,
        ':description' => $description,
        ':dateCreate' => time() - 9,
        ':author' => $managerId,
        ':manager' => $managerId,
        ':worker' => $userId,
        ':companyId' => $companyId,
        ':datedone' => $datedone
    ];
    $addTaskQuery->execute($addTaskQueryData);
    $profileTaskId = $pdo->lastInsertId();

    // Событие о создании задачи
    $eventInviteTaskData = [
        ':action' => 'createinittask',
        ':taskId' => $profileTaskId,
        ':authorId' => 1,
        ':recipientId' => $userId,
        ':companyId' => $companyId,
        ':datetime' => time()-8,
        ':comment' => strtotime('midnight'),
        ':viewStatus' => 0,
    ];
    $addEventQuery->execute($eventInviteTaskData);

    // Комментарий о создании задачи
    resetViewStatus($profileTaskId);
    $commentData = [
        ':status' => 'system',
        ':commentText' => 'taskcreate',
        ':iduser' => 1,
        ':idtask' => $profileTaskId,
        ':datetime' => time() - 8,
    ];
    $addTaskCommentsQuery->execute($commentData);

    // Комментарий о новом ответственном
    $commentData[':commentText'] = 'newworker:' . $userId;
    $commentData[':datetime'] = time() - 7;
    $addTaskCommentsQuery->execute($commentData);

    // Комментарий о письме с ссылкой
    $profileTaskCommentQuery = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
    $profileTaskCommentData = [
        ':status' => 'comment',
        ':iduser' => 1,
        ':idtask' => $profileTaskId,
        'view' => 0,
        ':datetime' => time()-6,
    ];
    if ($forCeo) {
        $profileTaskCommentData[':comment'] = 'Не забудьте загрузить аватарку, пускай сотрудники сразу увидят, кто здесь Босс!';
    } else {
        $profileTaskCommentData[':comment'] = 'Не забудьте загрузить аватарку!';
    }
    $profileTaskCommentQuery->execute($profileTaskCommentData);
    $profileCommentId = $pdo->lastInsertId();

    // Событие о новом комментарии
    addCommentEvent($profileTaskId, $profileCommentId, true, -6);

    if ($forCeo) {
        // Создание задачи Пригласить сотрудника
        $name = 'Пригласите сотрудника';
        $datedone = strtotime('midnight +1 day');
        $description = '((parag))Пригласите сотрудника, отправив ему приглашение на странице Компания - ((link:https://s.lusy.io/invite/))Пригласить сотрудников((link_end))((parag_end))';
        $addInviteTaskData = [
            ':name' => $name,
            ':description' => $description,
            ':dateCreate' => time()-5,
            ':author' => $managerId,
            ':manager' => $managerId,
            ':worker' => $userId,
            ':companyId' => $companyId,
            ':datedone' => $datedone,
        ];
        $addTaskQuery->execute($addInviteTaskData);
        $InviteTaskId = $pdo->lastInsertId();

        // Событие о создании задачи
        $eventInviteTaskData = [
            ':action' => 'createinittask',
            ':taskId' => $InviteTaskId,
            ':authorId' => 1,
            ':recipientId' => $userId,
            ':companyId' => $companyId,
            ':datetime' => time()-4,
            ':comment' => $datedone,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventInviteTaskData);

        // Комментарий о создании задачи
        resetViewStatus($InviteTaskId);
        $createCommentInviteTaskData = [
            ':status' => 'system',
            ':commentText' => 'taskcreate',
            ':iduser' => 1,
            ':idtask' => $InviteTaskId,
            ':datetime' => time()-4,
        ];
        $addTaskCommentsQuery->execute($createCommentInviteTaskData);

        // Комментарий о новом ответственном
        $createCommentInviteTaskData[':commentText'] = 'newworker:' . $userId;
        $createCommentInviteTaskData[':datetime'] = time() - 3;
        $addTaskCommentsQuery->execute($createCommentInviteTaskData);

        // Комментарий о письме с ссылкой
        $inviteTaskCommentQuery = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
        $inviteTaskCommentData = [
            ':comment' => 'После отправки приглашения сотрудник получит письмо с ссылкой для активации его аккаунта',
            ':status' => 'comment',
            ':iduser' => 1,
            ':idtask' => $InviteTaskId,
            'view' => 0,
            ':datetime' => time()-2,
        ];
        $inviteTaskCommentQuery->execute($inviteTaskCommentData);
        $inviteCommentId = $pdo->lastInsertId();
        // Событие о новом комментарии
        addCommentEvent($InviteTaskId, $inviteCommentId, true, -2);
    }

}

/**Подсчитывает оставшееся свообдное место для файлов
 * и число задач,которые можно назначить в этом месяце
 * (для платного тарифа всегда возвращает 10000)
 *
 * @return array Ассоциативный массив с ключами:
 * 'space' - остаток свободного места
 * 'tasks' - осташиеся задачи
 */
function getRemainingLimits()
{
    global $idc;
    global $pdo;
    $tariff = DBOnce('tariff', 'company', 'id=' . $idc);

    require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

    $providedStorageSpace = getProvidedStorageSpace();
    $companyTotalFilesSize = getCompanyFilesTotalSize();
    $emptySpace = $providedStorageSpace - $companyTotalFilesSize;
    if ($emptySpace < 0) {
        $emptySpace = 0;
    }
    if ($tariff == 1) {
        $tasksRemaining = 10000;
    } else {
        $tasksPerMonthLimit = 150;
        $createdTasksQuery = $pdo->prepare("SELECT COUNT(DISTINCT task_id) FROM events WHERE company_id = :companyId AND action = 'createtask' AND datetime > :firstDay");
        $createdTasksQuery->execute(array(':companyId' => $idc, ':firstDay' => strtotime(date('1.m.Y'))));
        $createdTasks = $createdTasksQuery->fetch(PDO::FETCH_COLUMN);
        $tasksRemaining = $tasksPerMonthLimit - $createdTasks;
        if ($tasksRemaining < 0) {
            $tasksRemaining = 0;
        }
    }
    $result = [
        'space' => $emptySpace,
        'tasks' => $tasksRemaining
    ];
    return $result;
}

function addMailToQueue($function, $args, $id, $eventId = null)
{
    global $pdo;
    $argsJson = json_encode($args);
    $addToQueueQuery = $pdo->prepare("INSERT INTO mail_queue(function_name, args, user_id, start_time, event_id) VALUES (:functionName, :args, :userId, :startTime, :eventId)");
    if (is_array($id)) {
        foreach ($id as $key => $oneId) {
            if ($oneId == 1) {
                continue;
            }
            $addToQueueQuery->execute(array(':functionName' => $function, ':args' => $argsJson, ':userId' => $oneId, ':startTime' =>time(), ':eventId' => $eventId[$oneId]));
        }
    } elseif ($id != 1) {
        $addToQueueQuery->execute(array(':functionName' => $function, ':args' => $argsJson, ':userId' => $id, ':startTime' =>time(), ':eventId' => $eventId));
    }
}

function removeMailFromQueue($queueId)
{
    global $pdo;
    $removeFromQueueQuery = $pdo->prepare("DELETE FROM mail_queue WHERE queue_id = :queueId");
    $removeFromQueueQuery->execute(array(':queueId' => $queueId));
}

function checkViewStatus($eventId, $isMessage = false)
{
    global $pdo;
    if ($isMessage) {
        $viewStatusQuery = $pdo->prepare("SELECT view_status FROM mail WHERE message_id = :id");
    } else {
        $viewStatusQuery = $pdo->prepare("SELECT view_status FROM events WHERE event_id = :id");
    }
    $viewStatusQuery->execute([':id' => $eventId]);
    $result = $viewStatusQuery->fetch(PDO::FETCH_COLUMN);
    return (boolean) $result;
}

function getCeoMail($companyId)
{
    global $pdo;
    $seoMailQuery = $pdo->prepare("SELECT email FROM users WHERE idcompany = :companyId AND role = 'ceo'");
    $seoMailQuery->execute([':companyId' => $companyId]);
    $seoMail = $seoMailQuery->fetch(PDO::FETCH_COLUMN);
    return $seoMail;
}
function getCeoId($companyId)
{
    global $pdo;
    $seoIdQuery = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND role = 'ceo'");
    $seoIdQuery->execute([':companyId' => $companyId]);
    $seoId = $seoIdQuery->fetch(PDO::FETCH_COLUMN);
    return $seoId;
}

function sendSubscribePremiumEmailNotification($companyId, $tariffName, $subscribeUntil, $nextChargeDate, $freePeriod)
{
    global $pdo;
    $seoMail = getCeoMail($companyId);
    $seoId = getCeoId($companyId);
    $notifications = getNotificationSettings($seoId);
    if (!$notifications['payment']) {
        return;
    }

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($seoMail);
        $mail->isHTML();
        $mail->Subject = "Успешная оплата подписки в Lusy.io";
        $args = [
            'tariffName' => $tariffName,
            'subscribeUntil' => $subscribeUntil,
            'nextChargeDate' => $nextChargeDate,
        ];
        if ($freePeriod) {
            $mail->setMessageContent('subscribe-premium-free', $args);
        } else {
            $mail->setMessageContent('subscribe-premium', $args);
        }
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendSubscribePromoEmailNotification($companyId, $tariffName, $promocode)
{
    $seoMail = getCeoMail($companyId);
    $seoId = getCeoId($companyId);
    $notifications = getNotificationSettings($seoId);
    if (!$notifications['payment']) {
        return;
    }

    require_once __ROOT__ . '/engine/backend/functions/payment-functions.php';
    $promocodeInfo = getPromocodeInfo($promocode);

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($seoMail);
        $mail->isHTML();
        $mail->Subject = "Подключение тарифа в Lusy.io";
        $args = [
            'tariffName' => $tariffName,
            '$freeDays' => $promocodeInfo['days_to_add'],
        ];
        $mail->setMessageContent('subscribe-promo-free', $args);
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}
function sendSubscribeProlongationFailedEmailNotification($companyId, $tariffName, $cardDigits)
{
    $seoMail = getCeoMail($companyId);
    $seoId = getCeoId($companyId);
    $notifications = getNotificationSettings($seoId);
    if (!$notifications['payment']) {
        return;
    }

    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($seoMail);
        $mail->isHTML();
        $mail->Subject = "Успешная оплата подписки в Lusy.io";
        $args = [
            'tariffName' => $tariffName,
            'cardDigits' => $cardDigits,
        ];
        $mail->setMessageContent('premium-prolongation-failed.php', $args);
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function sendActivationLink($companyId, $password = null)
{
    require_once __ROOT__ . '/engine/backend/functions/reg-functions.php';
    $activationCode = createActivationCode($companyId);
    require_once __ROOT__ . '/engine/phpmailer/LusyMailer.php';
    $seoMail = getCeoMail($companyId);
    require_once __ROOT__ . '/engine/phpmailer/Exception.php';
    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    try {
        $mail->addAddress($seoMail);
        $mail->isHTML();
        $args = [
            'activationLink' => 'https://s.lusy.io/activate/' . $companyId . '/' . $activationCode . '/',
        ];
        if (is_null($password)) {
            $mail->Subject = "Подтверждение e-mail";
            $mail->setMessageContent('company-activation', $args);
        } else {
            $mail->Subject = "Регистрация в Lusy.io";
            $args['email'] = $seoMail;
            $args['password'] = $password;
            $mail->setMessageContent('company-activation-password', $args);
        }
        $mail->send();
    } catch (Exception $e) {
        return;
    }
}

function getFreePremiumLimits($companyId)
{
    global $pdo;
    $tryPremiumLimitsQuery = $pdo->prepare("SELECT premium_free_access FROM company WHERE id = :companyId");
    $tryPremiumLimitsQuery->execute([':companyId' =>$companyId]);
    $tryPremiumLimits = $tryPremiumLimitsQuery->fetch(PDO::FETCH_COLUMN);
    $result = [
        'task' => 0,
        'report' => 0,
        'cloud' => 0,
        'edit' => 0,
    ];
    if (!is_null($tryPremiumLimits)) {
        $limits = json_decode($tryPremiumLimits, true);
        foreach ($limits as $key => $value) {
            $result[$key] = $value;
        }
    }
    return $result;
}

function updateFreePremiumLimits($companyId, $updatingType)
{
    global $pdo;
    $currentLimits = getFreePremiumLimits($companyId);
    if (isset($currentLimits[$updatingType])) {
        $currentLimits[$updatingType]++;
        $newLimitsJson = json_encode($currentLimits);
        $updateLimitsQuery = $pdo->prepare("UPDATE company SET premium_free_access = :newLimitsJson WHERE id = :companyId");
        $updateLimitsQuery->execute([':companyId' =>$companyId, ':newLimitsJson' => $newLimitsJson]);
    }
}

function getDisplayUserName($userId)
{
    global $pdo;
    $userFullNameQuery = $pdo->prepare('SELECT name, surname, email FROM users u WHERE id = :userId');
    $userFullNameQuery->execute([':userId' => $userId]);
    $userFullNameResult = $userFullNameQuery->fetch(PDO::FETCH_ASSOC);
    if (trim($userFullNameResult['name'] . ' ' . $userFullNameResult['surname']) == '') {
        $userFullName = $userFullNameResult['email'];
    } else {
        $userFullName = trim($userFullNameResult['name'] . ' ' . $userFullNameResult['surname']);
    }
    return $userFullName;
}

function addToErrorLog($text)
{
    $file = __ROOT__ . '/custom-error.log';
    $current = file_get_contents($file);
    $current .= date('d.m.Y H:i:s');
    $current .= "\n";
    $current .= $text;
    $current .= "\n";
    file_put_contents($file, $current);
}

function sanitizeCloudUploads($googleFiles, $dropboxFiles) {
    $cloudFiles = [
        'google' => [],
        'dropbox' => [],
    ];
    foreach ($googleFiles as $k => $v) {
        $cloudFiles['google'][] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    foreach ($dropboxFiles as $k => $v) {
        $cloudFiles['dropbox'][] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    return $cloudFiles;
}

function generateRandomString($numberOfWords) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($n = 0; $n < $numberOfWords; $n++) {
        $word = '';
        $length = rand(2,14);
        for ($i = 0; $i < $length; $i++) {
            $word .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= ucfirst($word) . ' ';
    }
    return $randomString;
}

function encodeTextTags($text) {
    //<code>code</code></p>
    $encoder = [
        '~<h1>~' => '((head1))',
        '~</h1>~' => '((head1_end))',
        '~<h2>~' => '((head2))',
        '~</h2>~' => '((head2_end))',
        '~<h3>~' => '((head3))',
        '~</h3>~' => '((head3_end))',
        '~<h4>~' => '((head4))',
        '~</h4>~' => '((head4_end))',
        '~<h5>~' => '((head5))',
        '~</h5>~' => '((head5_end))',
        '~<h6>~' => '((head6))',
        '~</h6>~' => '((head6_end))',
        '~<p>~' => '((parag))',
        '~<p class="ql-align-justify">~' => '((parag))',
        '~&nbsp;~' => ' ',
        '~</p>~' => '((parag_end))',
        '~<strong>~' => '((strong))',
        '~</strong>~' => '((strong_end))',
        '~<em>~' => '((italic))',
        '~</em>~' => '((italic_end))',
        '~<u>~' => '((underl))',
        '~</u>~' => '((underl_end))',
        '~<a href="(.+?)" target="_blank">~' => '((link:$1))',
        '~<a href="https://s.lusy.io/settings/" target="_blank" style="color\: rgb\(32, 36, 41\);">~' => '((link:https://s.lusy.io/settings/))',
        '~</a>~' => '((link_end))',
        '~<ol>~' => '((orderlist))',
        '~</ol>~' => '((orderlist_end))',
        '~<ul>~' => '((unorderlist))',
        '~</ul>~' => '((unorderlist_end))',
        '~<li>~' => '((listelem))',
        '~</li>~' => '((listelem_end))',
        '~<code>~' => '((codeinside))',
        '~</code>~' => '((codeinside_end))',
        '~<br>~' => '((breakline))',
    ];
    $needles = array_keys($encoder);
    $replacements = array_values($encoder);
    $result = preg_replace($needles, $replacements, $text);
    return $result;
}

function decodeTextTags($text) {
    $encoder = [
        '<h1>' => '~\(\(head1\)\)~',
        '</h1>' => '~\(\(head1_end\)\)~',
        '<h2>' => '~\(\(head2\)\)~',
        '</h2>' => '~\(\(head2_end\)\)~',
        '<h3>' => '~\(\(head3\)\)~',
        '</h3>' => '~\(\(head3_end\)\)~',
        '<h4>' => '~\(\(head4\)\)~',
        '</h4>' => '~\(\(head4_end\)\)~',
        '<h5>' => '~\(\(head5\)\)~',
        '</h5>' => '~\(\(head5_end\)\)~',
        '<h6>' => '~\(\(head6\)\)~',
        '</h6>' => '~\(\(head6_end\)\)~',
        '<p>' => '~\(\(parag\)\)~',
        '</p>' => '~\(\(parag_end\)\)~',
        '<strong>' => '~\(\(strong\)\)~',
        '</strong>' => '~\(\(strong_end\)\)~',
        '<em>' => '~\(\(italic\)\)~',
        '</em>' => '~\(\(italic_end\)\)~',
        '<u>' => '~\(\(underl\)\)~',
        '</u>' => '~\(\(underl_end\)\)~',
        '<a href="$1" target="_blank">' => '~\(\(link:(.+?)\)\)~',
        '</a>' => '~\(\(link_end\)\)~',
        '<ol>' => '~\(\(orderlist\)\)~',
        '</ol>' => '~\(\(orderlist_end\)\)~',
        '<ul>' => '~\(\(unorderlist\)\)~',
        '</ul>' => '~\(\(unorderlist_end\)\)~',
        '<li>' => '~\(\(listelem\)\)~',
        '</li>' => '~\(\(listelem_end\)\)~',
        '<code>' => '~\(\(codeinside\)\)~',
        '</code>' => '~\(\(codeinside_end\)\)~',
        '<br>' => '~\(\(breakline\)\)~',
    ];
    $needles = array_values($encoder);
    $replacements = array_keys($encoder);
    $result = preg_replace($needles, $replacements, $text);
    return $result;
}
