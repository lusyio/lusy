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

    $possibleActions = ['createtask', 'viewtask', 'comment', 'overdue', 'review', 'postpone', 'confirmdate', 'canceldate',
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
        }

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
        ];
        $eventDataForWorker = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $taskWorker,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 1,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $addEventQuery->execute($eventDataForWorker);
        $addEventQuery->execute($eventDataForManager);
        $managerEventId = $pdo->lastInsertId();

        $pushData = [
            'type' => 'task',
            'eventId' => $managerEventId,
        ];
        $sendToCometQuery = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
        $sendToCometQuery->execute(array(':id' => $taskManager, ':type' => json_encode($pushData)));
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
    global $id;
    $newTaskCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action NOT LIKE "comment"');
    $overdueCount = DBOnce('count(*)','tasks','(worker='.$id.' or manager='.$id.') and status="overdue"');
    $newCommentCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action LIKE "comment"');
    $newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');

    $result = [
        'task' => $newTaskCount,
        'hot' => $overdueCount,
        'comment' => $newCommentCount,
        'mail' => $newMailCount,
    ];
    return $result;
}
