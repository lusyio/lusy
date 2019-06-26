<?php

global $pdo;
global $cometPdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'sendMessage') {
    $mes = link_it($_POST['mes']);
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $recipientId = filter_var($_POST['recipientId'], FILTER_SANITIZE_NUMBER_INT);
    $messageTime = time();
    if (!empty($mes)) {
        $dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array('mes' => $mes, 'sender' => $id, 'recipient' => $recipientId, 'datetime' => $messageTime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            echo 'has file';
            uploadAttachedFiles('conversation', $messageId);
        }


        $cometSql = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'new', :jsonMesData)");
        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>";
        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
        $mesData = [
            'senderId' => $id,
            'recipientId' => $recipientId,
            'messageId' => $messageId,
        ];
        $jsonMesData = json_encode($mesData);
        $cometSql->execute(array(':jsonMesData' => $jsonMesData, ':id' => $recipientId));
        $cometSql->execute(array(':jsonMesData' => $jsonMesData, ':id' => $id));
    }
}
if ($_POST['module'] == 'sendMessageToChat') {
    $mes = link_it($_POST['mes']);
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $messageTime = time();
    if (!empty($mes)) {
        $dbh = "INSERT INTO chat (text, author_id, datetime) VALUES (:message, :authorId, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array(':message' => $mes, ':authorId' => $id, ':datetime' => $messageTime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            uploadAttachedFiles('chat', $messageId);
        }
        $cometSql = $cometPdo->prepare("INSERT INTO pipes_messages (name, event, message) VALUES (:channelName, 'newChat', :jsonMesData)");
        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>";
        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
        $mesData = [
            'messageId' => $messageId,
        ];
        $jsonMesData = json_encode($mesData);
        $cometSql->execute(array(':channelName' => getCometTrackChannelName(), ':jsonMesData' => $jsonMesData));
    }
}

if ($_POST['module'] == 'updateMessages') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getMessageById($messageId);
    foreach ($messages as $message) {
        include 'engine/frontend/other/message.php';
    }
}
if ($_POST['module'] == 'updateChat') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getChatMessageById($messageId);
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

function getChatMessageById($messageId)
{
    global $pdo;
    global $id;
    global $idc;
    $query = "SELECT c.message_id, c.text as mes, c.author_id AS sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE u.idcompany = :companyId AND message_id = :messageId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc,':messageId' => $messageId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $id, true);
    return $result;
}

function prepareMessages(&$messages, $userId, $forChat = false)
{
    global $pdo;
    foreach ($messages as &$message) {
        $message['status'] = '';
        $message['owner'] = false;

        if ($message['sender'] == $userId) {
            $message['owner'] = true;
            $message['author'] = 'Вы';
            if ($forChat) {
                $message['status'] = '';
                $message['view_status'] = 1;
            } else {
                if ($message['view_status']) {
                    $message['status'] = ' (прочитано)';
                } else {
                    $message['status'] = ' (не прочитано)';
                }
            }
        } else {
            if ($forChat) {
                $message['status'] = '';
                $message['view_status'] = 1;
            }
            $message['author'] = fiomess($message['sender']);
        }
        if ($forChat) {
            $commentType = 'chat';
        } else {
            $commentType = 'conversation';
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted FROM uploads WHERE (comment_id = :messageId) AND comment_type = :commentType');
        $filesQuery->execute(array(':messageId' => $message['message_id'], ':commentType' => $commentType));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        if (count($files) > 0) {
            $message['files'] = $files;
        } else {
            $message['files'] = [];
        }

    }
}

function sendMessageToChat()
{

}