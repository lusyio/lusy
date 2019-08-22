<?php

function fiomess($iduser) {
    global $pdo;
    $name = DBOnce('name','users','id='.$iduser);
    $surname = DBOnce('surname','users','id='.$iduser);
    $result = trim( $name . ' ' . $surname);
    if ($result == '') {
        $result = DBOnce('email','users','id='.$iduser);
    }
    return $result;
}

function lastmess($interlocutorId, $userId) {
    global $pdo;
    global $id;
    $sql = $pdo->prepare("SELECT sender, mes, datetime FROM mail WHERE (sender = :userId or recipient = :userId ) and (sender = :id or recipient = :id) order by datetime DESC limit 1");
    $sql->execute(array(':userId' => $interlocutorId, ':id' => $userId));
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
}
function lastChatMessage() {
    global $pdo;
    global $idc;
    $sql = $pdo->prepare("SELECT c.message_id, c.text as mes, c.author_id as sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE c.company_id = :companyId order by datetime DESC limit 1");
    $sql->execute(array(':companyId' => $idc));
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function numberOfNewMessages($idSender, $userId)
{
    global $id;
    $count = DBOnce('COUNT(*)', 'mail', 'recipient=' . $userId . ' AND sender=' . $idSender . ' AND view_status=0');
    return $count;
}

function numberOfNewChatMessages()
{
    global $id;
    global $idc;
    global $pdo;

    $lastViewedMessage = DBOnce('last_viewed_chat_message', 'users', 'id = ' . $id);
    $numberQuery = $pdo->prepare("SELECT COUNT(DISTINCT message_id) FROM chat WHERE company_id = :companyId AND message_id > :lastViewedMessage");
    $numberQuery->execute(array(':companyId' => $idc, ':lastViewedMessage' => $lastViewedMessage));
    $result = $numberQuery->fetch(PDO::FETCH_COLUMN);
    return $result;
}

function getMessages($userId, $interlocutorId)
{
    global $pdo;
    $query = "SELECT message_id, mes, sender, recipient, datetime, view_status FROM `mail` WHERE (`sender` = :userId AND `recipient` = :interlocutorId) OR (`sender` = :interlocutorId AND `recipient` = :userId) ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId, ':interlocutorId' => $interlocutorId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $userId);
    return $result;
}

function getChatMessages()
{
    global $pdo;
    global $id;
    global $idc;
    $query = "SELECT c.message_id, c.text as mes, c.author_id AS sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE c.company_id = :companyId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareChatMessages($result, $id);
    return $result;
}

function setMessagesViewStatus($userId, $interlocutorId)
{
    global $pdo;
    $query = "UPDATE mail SET view_status = 1 WHERE `sender` = :interlocutorId AND `recipient` = :userId AND `view_status` = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId, ':interlocutorId' => $interlocutorId));
}

function prepareChatMessages(&$messages, $userId)
{
    global $pdo;
    $lastViewedMessage = DBOnce('last_viewed_chat_message', 'users', 'id = ' . $userId);

    foreach ($messages as &$message) {
        $message['status'] = '';
        $message['owner'] = false;

        if ($message['sender'] == $userId) {
            $message['owner'] = true;
            $message['author'] = 'Вы';
            $message['status'] = '';
            $message['view_status'] = 1;
        } else {
            if ($message['message_id'] > $lastViewedMessage) {
                $message['view_status'] = 0;
            } else {
                $message['view_status'] = 1;
            }
            $message['status'] = '';
            $message['author'] = fiomess($message['sender']);
        }
        $commentType = 'chat';
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud FROM uploads WHERE (comment_id = :messageId) AND comment_type = :commentType');
        $filesQuery->execute(array(':messageId' => $message['message_id'], ':commentType' => $commentType));
        $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
        if (count($files) > 0) {
            $message['files'] = $files;
        } else {
            $message['files'] = [];
        }

    }
}

function getMessageById($messageId, $userId)
{
    global $pdo;
    global $id;
    $query = "SELECT message_id, mes, sender, recipient, datetime, view_status FROM `mail` WHERE (`sender` = :userId OR `recipient` = :userId) AND message_id=:messageId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId,':messageId' => $messageId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $id);
    return $result;
}

function getChatMessageById($messageId)
{
    global $pdo;
    global $id;
    global $idc;
    $query = "SELECT c.message_id, c.text as mes, c.author_id AS sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE company_id = :companyId AND message_id = :messageId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc,':messageId' => $messageId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $id, true);
    return $result;
}

function prepareMessages(&$messages, $userId, $forChat = false)
{
    global $id;
    global $idc;
    global $pdo;
    if ($forChat) {
        $lastViewedMessage = DBOnce('last_viewed_chat_message', 'users', 'id = ' . $id);
    }
    foreach ($messages as &$message) {
        $message['status'] = '';
        $message['owner'] = false;

        if ($message['sender'] == $userId || ($message['sender'] == 1 && $idc == 1)) {
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
                if ($message['message_id'] > $lastViewedMessage) {
                    $message['view_status'] = 0;
                } else {
                    $message['view_status'] = 1;
                }
                $message['status'] = '';
            }
            $message['author'] = fiomess($message['sender']);
        }
        if ($forChat) {
            $commentType = 'chat';
        } else {
            $commentType = 'conversation';
        }
        $filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, is_deleted, cloud FROM uploads WHERE (comment_id = :messageId) AND comment_type = :commentType');
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



function markMessageAsRead($messageId)
{
    global $pdo;
    $markAsReadQuery = $pdo->prepare("UPDATE mail SET view_status = 1 WHERE message_id = :messageId");
    $markAsReadQuery->execute(array(':messageId' => $messageId));
}

function markChatMessageAsRead($messageId)
{
    global $id;
    global $pdo;
    $markChatAsReadQuery = $pdo->prepare("UPDATE users SET last_viewed_chat_message = :messageId WHERE id = :userId AND last_viewed_chat_message < :messageId");
    $markChatAsReadQuery->bindValue(':userId', (int) $id, PDO::PARAM_INT);
    $markChatAsReadQuery->bindValue(':messageId', (int) $messageId, PDO::PARAM_INT);
    $markChatAsReadQuery->execute();
}

function markChatAsRead()
{
    $lastChatMessageId = lastChatMessage()['message_id'];
    markChatMessageAsRead($lastChatMessageId);
}

function deleteMessageFromChat($messageId)
{
    global $pdo;
    $filesQuery =  $pdo->prepare("SELECT file_id, file_path FROM `uploads` WHERE comment_id = :messageId and comment_type = 'chat'");
    $filesQuery->execute(array(':messageId' => $messageId));
    $files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
    if (count($files) > 0) {
        $deleteQuery = 'DELETE FROM `uploads` WHERE file_id = :fileId';
        $deleteDbh = $pdo->prepare($deleteQuery);
        foreach ($files as $file) {
            unlink($file['file_path']);
            $deleteDbh->execute(array(':fileId' => $file['file_id']));
        }
    }
    $deleteMessageQuery = $pdo->prepare("DELETE from chat where message_id = :messageId");
    $deleteMessageQuery->execute(array(':messageId' => $messageId));
}

function sendMessageToAllCeo($messageText)
{
    global $pdo;
    global $cometPdo;
    $ceoListQuery = $pdo->prepare("SELECT id FROM users WHERE role = 'ceo' AND is_fired = 0 AND id > 1");
    $ceoListQuery->execute();
    $ceoList = $ceoListQuery->fetchAll(PDO::FETCH_COLUMN);

    $sendMessageQuery = $pdo->prepare("INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:message, :sender, :recipient, :datetime)");
    $cometData = [];
    foreach ($ceoList as $ceoId) {
        $sendMessageQuery->execute(array(':message' => $messageText, ':sender' => 1, ':recipient' => $ceoId, ':datetime' => time()));
        $messageId = $pdo->lastInsertId();
        $mesData = [
            'senderId' => 1,
            'recipientId' => $ceoId,
            'messageId' => $messageId,
        ];
        $cometData[$ceoId] = $mesData;
    }
    $cometPdo->multipleSendNewMailMessage($cometData);

}

function sendMessageToAllUsers($messageText)
{
    global $pdo;
    global $cometPdo;
    $ceoListQuery = $pdo->prepare("SELECT id FROM users WHERE is_fired = 0 AND id > 1");
    $ceoListQuery->execute();
    $ceoList = $ceoListQuery->fetchAll(PDO::FETCH_COLUMN);

    $sendMessageQuery = $pdo->prepare("INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:message, :sender, :recipient, :datetime)");
    $cometData = [];
    foreach ($ceoList as $ceoId) {
        $sendMessageQuery->execute(array(':message' => $messageText, ':sender' => 1, ':recipient' => $ceoId, ':datetime' => time()));
        $messageId = $pdo->lastInsertId();
        $mesData = [
            'senderId' => 1,
            'recipientId' => $ceoId,
            'messageId' => $messageId,
        ];
        $cometData[$ceoId] = $mesData;
    }
    $cometPdo->multipleSendNewMailMessage($cometData);
}
