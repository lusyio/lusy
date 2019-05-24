<?php

global $pdo;
global $cometPdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'sendMessage') {
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_SPECIAL_CHARS);
    $recipientId = filter_var($_POST['recipientId'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($mes)) {
        $dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array('mes' => $mes, 'sender' => $id, 'recipient' => $recipientId, 'datetime' => $datetime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            echo 'has file';
            uploadAttachedFiles('conversation', $messageId);
        }


        $cometSql = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :jsonMesData)");
//        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>"; // закомментировано для отладки ->
//        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
//        $mesData = [
//            'senderId' => $id,
//            'recipientId' => $recipientId,
//            'messageId' => $messageId,
//        ];
//        $jsonMesData = json_encode($mesData); // <-закомментировано для отладки
        $jsonMesData = 'test message'; // переменная для отладки
        echo 'получатель' . $recipientId;
        echo 'отправитель' . $id;
        echo 'направлено получателю' . $cometSql->execute(array(':jsonMesData' => $jsonMesData, ':id' => $recipientId));
        echo 'направлено отправителю' . $cometSql->execute(array(':jsonMesData' => $jsonMesData, ':id' => $id));
    }
}

if ($_POST['module'] == 'updateMessages') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getMessageById($messageId);
    foreach ($messages as $message) {
        include 'engine/frontend/other/message.php';
    }
}

function fiomess($iduser)
{
    global $pdo;
    $fio = DBOnce('name', 'users', 'id=' . $iduser) . ' ' . DBOnce('surname', 'users', 'id=' . $iduser);
    return $fio;
}

function getMessageById($messageId)
{
    global $pdo;
    global $id;
    $query = "SELECT message_id, mes, sender, recipient, datetime, view_status FROM `mail` WHERE (`sender` = :userId OR `recipient` = :userId) AND message_id=:messageId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $id,':messageId' => $messageId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $id);
    return $result;
}

function prepareMessages(&$messages, $userId)
{
    global $pdo;
    foreach ($messages as &$message) {
        $message['status'] = '';
        $message['owner'] = false;

        if ($message['sender'] == $userId) {
            $message['owner'] = true;
            $message['author'] = 'Вы';
            if ($message['view_status']) {
                $message['status'] = ' (прочитано)';
            } else {
                $message['status'] = ' (не прочитано)';
            }
        } else {
            $message['author'] = fiomess($message['sender']);
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted FROM uploads WHERE (comment_id = :messageId) AND comment_type = :commentType');
        $filesQuery->execute(array(':messageId' => $message['message_id'], ':commentType' => 'conversation'));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        if (count($files) > 0) {
            $message['files'] = $files;
        } else {
            $message['files'] = [];
        }

    }
}