<?php

global $pdo;
global $cometPdo;
global $datetime;
global $id;
global $idc;

if ($_POST['module'] == 'sendMessage') {
    $mes = $_POST['mes'];
    $recipientId = $_POST['recipientId'];

    if (!empty($mes)) {
        $dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array('mes' => $mes, 'sender' => $id, 'recipient' => $recipientId, 'datetime' => $datetime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            echo 'has file';
            uploadAttachedFiles('conversation', $messageId);
        }

        $cometQuery = "INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :mes)";
        $cometSql = $cometPdo->prepare($cometQuery);
        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>";
        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
        $cometSql->execute(array(':mes' => $messageCometForRecipient, ':id' => $recipientId));
        $cometSql->execute(array(':mes' => $messageCometForSender, ':id' => $id));

    }
}

function fiomess($iduser)
{
    global $pdo;
    $fio = DBOnce('name', 'users', 'id=' . $iduser) . ' ' . DBOnce('surname', 'users', 'id=' . $iduser);
    return $fio;
}

/**
 * Загружает каждый файл из массива _FILES в upload/files/
 * и добавляет информацию о нем в бд в таблицу uploads
 * @param string $type Type to which the files is attached: 'task' or 'comment'
 * @param int $id Id of specified type event
 */
function uploadAttachedFiles($type, $id)
{
    global $pdo;
    global $idc;
    $types = ['task', 'comment', 'conversation'];
    if (!in_array($type, $types)) {
        return;
    }

    if ($type == 'comment') {
        global $idtask;
    } else {
        $idtask = $id;
    }

    $dirName = 'upload/files/' . $idtask;
    if (!realpath($dirName)) {
        mkdir($dirName, 0777, true);
    }

    $sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type, company_id, is_deleted) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType, :companyId, :isDeleted)');
    foreach ($_FILES as $file) {
        $fileName = basename($file['name']);
        $hashName = md5_file($file['tmp_name']);
        while (file_exists($dirName . '/' . $hashName)) {
            $hashName = md5($hashName);
        }
        $filePath = $dirName . '/' . $hashName;
        $sql->execute(array(':fileName' => $fileName, ':fileSize' => $file['size'], ':filePath' => $filePath, ':commentId' => $id, ':commentType' => $type, ':companyId' => $idc, ':isDeleted' => 0));
        move_uploaded_file($file['tmp_name'], $filePath);
    }
}