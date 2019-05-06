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


        $cometQuery = "INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :mesId)";
        $cometSql = $cometPdo->prepare($cometQuery);
        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>";
        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
        $cometSql->execute(array(':mesId' => $messageId, ':id' => $recipientId));
        $cometSql->execute(array(':mesId' => $messageId, ':id' => $id));
    }
}

if ($_POST['module'] == 'updateMessages') {
    $messageId = $_POST['messageId'];
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
    $query = "SELECT message_id, mes, sender, recipient, datetime FROM `mail` WHERE (`sender` = :userId OR `recipient` = :userId) AND message_id=:messageId ORDER BY `datetime`";
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
        if ($message['sender'] == $userId) {
            $message['author'] = 'Вы';
        } else {
            $message['author'] = fiomess($message['sender']);
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id FROM uploads WHERE (comment_id = :messageId) AND comment_type = :commentType');
        $filesQuery->execute(array(':messageId' => $message['message_id'], ':commentType' => 'conversation'));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        if (count($files) > 0) {
            $message['files'] = $files;
        } else {
            $message['files'] = [];
        }

    }
}