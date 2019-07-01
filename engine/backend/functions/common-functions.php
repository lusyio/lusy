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

    require_once 'engine/backend/functions/storage-functions.php';

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

    $maxFileSize = 20 * 1024 * 1024;
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

function getCometTrackChannelName()
{
    global $idc;
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
        'senddate', 'workreturn', 'workdone', 'canceltask', 'changeworker', 'addcoworker', 'removecoworker', 'newuser',
        'newcompany', 'newachievement'];

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
            sendTaskWorkerEmailNotification($taskId, 'createtask');
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
            sendTaskWorkerEmailNotification($taskId, 'overdue');
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

        sendTaskManagerEmailNotification($taskId, 'review');
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
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
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

        sendTaskManagerEmailNotification($taskId, 'postpone');
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
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $eventDataForAuthor = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
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
            ':viewStatus' => 1,
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
        $eventDataForWorker = [
            ':action' => 'createtask',
            ':taskId' => $taskId,
            ':authorId' => $id,
            ':recipientId' => $recipientId,
            ':companyId' => $idc,
            ':datetime' => time(),
        ];
        $addEventQuery->execute($eventDataForWorker);
        $workerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $workerEventId,
        ];
        $sendToCometQuery->execute(array(':id' => $recipientId, ':type' => json_encode($pushData)));

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
            ':taskId' => '',
            ':recipientId' => $ceoId,
            ':authorId' => 1,
            ':companyId' => $idc,
            ':datetime' => time(),
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
            ':taskId' => '',
            ':recipientId' => $recipientId,
            ':authorId' => 1,
            ':companyId' => $idc,
            ':datetime' => time(),
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

        sendAchievementEmailNotification($id, $comment);
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
        ':taskId' => '',
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
    $fontFile = realpath('engine/backend/fonts/Roboto-Regular.ttf');

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

    $userQuery = $pdo->prepare('SELECT id, login, email, phone, name, surname, idcompany, role, points, activity, register_date, social_networks, about FROM users WHERE id = :userId AND idcompany = :companyId');
    $userQuery->execute(array(':userId' => $userId, ':companyId' => $idc));
    $userData = $userQuery->fetch(PDO::FETCH_ASSOC);
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

function addCommentEvent($taskId, $commentId)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $executorsQuery = $pdo->prepare('SELECT worker, manager FROM tasks WHERE id = :taskId');
    $executorsQuery->execute(array(':taskId' => $taskId));
    $executors = $executorsQuery->fetch(PDO::FETCH_ASSOC);
    $coworkersQuery = $pdo->prepare('SELECT worker_id FROM task_coworkers WHERE task_id = :taskId');
    $coworkersQuery->execute(array(':taskId' => $taskId));
    $coworkers = $coworkersQuery->fetchAll(PDO::FETCH_COLUMN);

    $recipients = $coworkers;
    $recipients[] = $executors['manager'];
    $recipients[] = $executors['worker'];
    array_unique($recipients);

    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
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
            $addEventQuery->execute($eventData);
            $eventId = $pdo->lastInsertId();

            $pushData = [
                'type' => 'comment',
                'eventId' => $eventId,
            ];
            $sendToCometQuery->execute(array(':id' => $recipient, ':type' => json_encode($pushData)));
        }
    }

    sendCommentEmailNotification($taskId, $id, $recipients, $commentId);

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
    require_once 'engine/backend/functions/mail-functions.php';

    global $id;
    $newTaskCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action NOT LIKE "comment"');
    $overdueCount = DBOnce('count(*)','tasks','(worker='.$id.' or manager='.$id.') and status="overdue"');
    $newCommentCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action LIKE "comment"');
    $newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');
    $newChatCount = numberOfNewChatMessages();

    $result = [
        'task' => $newTaskCount,
        'hot' => $overdueCount,
        'comment' => $newCommentCount,
        'mail' => $newMailCount + $newChatCount,
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

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';

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

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();
    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE t.id = :taskId");
    $companyNameQuery->execute(array(':taskId' => $taskId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);

    $managerMailQuery = $pdo->prepare("SELECT u.email FROM tasks t LEFT JOIN users u ON t.manager = u.id WHERE t.id = :taskId");
    $managerMailQuery->execute(array(':taskId' => $taskId));
    $managerMail = $managerMailQuery->fetch(PDO::FETCH_COLUMN);

    $workerNameQuery = $pdo->prepare("SELECT u.name, u.surname FROM tasks t LEFT JOIN users u ON t.worker = u.id WHERE t.id = :taskId");
    $workerNameQuery->execute(array(':taskId' => $taskId));
    $workerNameResult = $workerNameQuery->fetch(PDO::FETCH_ASSOC);
    $workerName = trim($workerNameResult['name'] . ' ' . $workerNameResult['surname']);

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
            $mail->Subject = "Вам отправлена на рассмотрение задача в Lusy.io";
            $mail->setMessageContent('task-review', $args);

        } elseif ($action == 'postpone') {
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

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    $companyNameQuery = $pdo->prepare("SELECT c.idcompany FROM tasks t LEFT JOIN company c ON t.idcompany = c.id WHERE t.id = :taskId");
    $companyNameQuery->execute(array(':taskId' => $taskId));
    $companyName = $companyNameQuery->fetch(PDO::FETCH_COLUMN);

    $authorNameQuery = $pdo->prepare("SELECT name, surname FROM users WHERE  id = :authorId");
    $authorNameQuery->execute(array(':authorId' => $authorId));
    $authorNameResult = $authorNameQuery->fetch(PDO::FETCH_ASSOC);
    $authorName = trim($authorNameResult['name'] . ' ' . $authorNameResult['surname']);

    $taskName = DBOnce('name', 'tasks', 'id=' . $taskId);

    $args = [
        'companyName' => $companyName,
        'taskId' => $taskId,
        'authorName' => $authorName,
        'taskName' => $taskName,
        'commentId' => $commentId,
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

function sendMessageEmailNotification($userId, $authorId)
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

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($userMail);
        $mail->isHTML();
        $mail->Subject = "Вам отправили личное сообщение в Lusy.io";
        $args = [
            'companyName' => $companyName,
            'authorName' => $authorName,
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

    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';

    $mail = new \PHPMailer\PHPMailer\LusyMailer();

    try {
        $mail->addAddress($userMail);
        $mail->isHTML();
        $mail->Subject = "Вы получили новое достижение в Lusy.io";
        $args = [
            'companyName' => $companyName,
            'achievementName' => $GLOBALS['_' . $achievementName],
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
    $notificationSettingsQuery = $pdo->prepare('SELECT user_id, task_create, task_overdue, comment, task_review, task_postpone, message, achievement FROM user_notifications WHERE user_id = :userId');
    $notificationSettingsQuery->execute(array(':userId' => $userId));
    $result = $notificationSettingsQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function createInitTask($userId, $companyId)
{
    global $pdo;

    require_once 'engine/backend/functions/task-functions.php';
    require_once 'engine/backend/functions/mail-functions.php';

    $managerId = 1;

    $name = 'Заполнить профиль';
    $description = '&#60;p class=&#34;ql-align-justify&#34;&#62;В настройках профиля&#38;nbsp;&#60;a href=&#34;https://s.lusy.io/settings/&#34; target=&#34;_blank&#34; style=&#34;color: rgb(32, 36, 41);&#34;&#62;https://s.lusy.io/settings/&#60;/a&#62;&#38;nbsp;Вы можете указать контактную информацию, рассказать о себе или добавить ссылки на Ваши социальные сети! А также, не забудьте загрузить свою фотографию.&#60;/p&#62;&#60;p class=&#34;ql-align-justify&#34;&#62;&#60;br&#62;&#60;/p&#62;&#60;p&#62;&#60;br&#62;&#60;/p&#62;';
    $datedone = strtotime(date('Y-m-d'));
    $worker = $userId;

    $query = "INSERT INTO tasks(name, description, datecreate, datedone, datepostpone, status, author, manager, worker, idcompany, report, view) VALUES (:name, :description, :dateCreate, :datedone, NULL, 'new', :author, :manager, :worker, :companyId, :description, '0') ";
    $sql = $pdo->prepare($query);
    $sql->execute(array(':name' => $name, ':description' => $description, ':dateCreate' => time(), ':author' => $managerId, ':manager' => $managerId, ':worker' => $worker, ':companyId' => $companyId, ':datedone' => $datedone));
    if ($sql) {
        $taskId = $pdo->lastInsertId();
        resetViewStatus($taskId);


        //addTaskCreateComments($idtask, $worker, []);
        $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

        $commentData = [
            ':status' => 'system',
            ':commentText' => 'taskcreate',
            ':iduser' => 1,
            ':idtask' => $taskId,
            ':datetime' => time(),
        ];
        $sql->execute($commentData);
        $commentData[':commentText'] = 'newworker:' . $userId;
        $sql->execute($commentData);


        //addEvent('createtask', $idtask, $datedone, $worker);
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment, :viewStatus)');
        $eventDataForWorker = [
            ':action' => 'createinittask',
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $userId,
            ':companyId' => $companyId,
            ':datetime' => time(),
            ':comment' => $taskId,
            ':viewStatus' => 0,
        ];
        $addEventQuery->execute($eventDataForWorker);
        //sendTaskWorkerEmailNotification($taskId, 'createtask');
    }

//    $mes = 'Зарегистрирован новый пользователь - ' . fiomess($userId) .'!';
//    $messageTime = time();
//        $dbh = "INSERT INTO chat (text, author_id, datetime) VALUES (:message, :authorId, :datetime) ";
//        $sql = $pdo->prepare($dbh);
//        $sql->execute(array(':message' => $mes, ':authorId' => 1, ':datetime' => $messageTime));
}
