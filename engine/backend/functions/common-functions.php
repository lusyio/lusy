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
    $maxFileSize = 20 * 1024 * 1024;
    $types = ['task', 'comment', 'conversation'];
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

function addEvent($action, $taskId, $recipientId)
{
    global $id;
    global $idc;
    global $pdo;
    global $cometPdo;

    $possibleActions = ['createtask', 'comment', 'overdue', 'review', 'postpone', 'confirmdate', 'canceldate',
        'senddate', 'workreturn', 'workdone', 'canceltask'];

    if (!in_array($action, $possibleActions)) {
        return false;
    }

    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime)');
    $eventData = [
        ':action' => $action,
        ':taskId' => $taskId,
        ':authorId' => $id,
        ':recipientId' => $recipientId,
        ':companyId' => $idc,
        'datetime' => date("Y-m-d H:i:s"),
    ];
    $addEventQuery->execute($eventData);
    if ($recipientId != $id) {

        $eventId = $pdo->lastInsertId();
        if ($action == 'comment') {
            $type = 'comment';
        } else {
            $type = 'task';
        }
        $pushData = [
            'type' => $type,
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
    $datetime = date("Y-m-d H:i:s");

    $sendToCometQuery  = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'newLog', :type)");
    $type = 'comment';

    foreach ($recipients as $recipient) {
        $eventData = [
            ':action' => $action,
            ':taskId' => $taskId,
            ':recipientId' => $recipient,
            ':authorId' => $id,
            ':companyId' => $idc,
            ':datetime' => $datetime,
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

    $possibleActions = ['newUserRegistered', 'newCompanyRegistered'];

    if (!in_array($action, $possibleActions)) {
        return;
    }

    $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
    $datetime = date("Y-m-d H:i:s");
    $eventData = [
        ':action' => $action,
        ':taskId' => '',
        ':recipientId' => '',
        ':authorId' => '',
        ':companyId' => $idc,
        ':datetime' => $datetime,
        ':comment' => $comment,
    ];
    $addEventQuery->execute($eventData);

    $companyUsersQuery = $pdo->prepare('SELECT id FROM users WHERE idcompany = :companyId');
    $companyUsersQuery->execute(array(':companyId' => $idc));

}

function getAvatarLink($userId)
{
    global $idc;
    $avatarPath = 'upload/avatar/' . $idc . '/' . $userId . '.png';
    $alterAvatarPath = 'upload/avatar/' . $idc . '/' . $userId . '-alter.png';
    if (file_exists($avatarPath)) {
        return $avatarPath;
    } elseif (file_exists($alterAvatarPath)) {
        return $alterAvatarPath;
    } else {
        createAlterAvatar($userId);
        return $alterAvatarPath;
    }
}

/**Генерирует аватарку пользователя из первых букв имени и фамилии в формате .png
 * и сохраняет в директории upload/avatar с именем $userId-alter.png
 * @param $userId int ID пользователя
 */
function createAlterAvatar($userId)
{
    global $idc;

    // Получаем из БД имя и фамилию пользователя, обрезаем до одной  буквы если пустые - заменяем точками
    $userName = trim(DBOnce('name', 'users', 'id='.$userId));
    $userSurname = trim(DBOnce('surname', 'users', 'id='.$userId));
    if ($userName == '') {
        $userName = '.';
    }
    if ($userSurname == '') {
        $userSurname = '.';
    }
    $letters = mb_strtoupper(' '. mb_substr($userName, 0, 1) . mb_substr($userSurname, 0, 1) . ' ');

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
        [56,192,208],[0,48,128],[69,176,230],[61,136,242],[230,69,69],[243,151,24],[71,204,193],[24,184,152],[0,83,156],[232,24,32],[184,193,217],[24,184,152],[168,200,232],[86,191,104],[143,152,79],[145,97,243],
    ];
    $colorSet = array_rand($colors);
    $backgroundColor = imagecolorallocate($im, $colors[$colorSet][0], $colors[$colorSet][1], $colors[$colorSet][2]);
    imagefill($im, 0, 0, $backgroundColor);

    //Измеряем ширину букв для центровки и наносим текст в полученные координаты
    $textCartesians = imagettfbbox($textSize, 0, $fontFile, $letters);
    $lettersWidth = abs($textCartesians[0] - $textCartesians[2]);
    $startX = ($imageHeight - $lettersWidth)  / 2;
    $startY = 126; // подбирается вручную, в зависимости от размера шрифта
    $text_color = imagecolorallocate($im, 255, 255, 255);
    imageTtfText($im, $textSize, 0, $startX, $startY, $text_color, $fontFile, $letters);

    $avatarDir = 'upload/avatar/' . $idc . '/';
    if (!realpath($avatarDir)) {
        mkdir($avatarDir, 0777, true);
    }
    $avatarPath = 'upload/avatar/' . $idc . '/' . $userId . '-alter.png';
    imagepng($im, $avatarPath);
    imagedestroy($im);
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
    foreach ($socialNetworks as $network => $link) {
        $userData['social'][$network] = $link;
    }
    return $userData;
}
