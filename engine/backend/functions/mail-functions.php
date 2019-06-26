<?php

function fiomess($iduser) {
    global $pdo;
    $name = DBOnce('name','users','id='.$iduser);
    $surname = DBOnce('surname','users','id='.$iduser);
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

function lastmess($iduser) {
    global $pdo;
    global $id;
    $sql = $pdo->prepare("SELECT sender, mes, datetime FROM mail WHERE (sender = :userId or recipient = :userId ) and (sender = :id or recipient = :id) order by datetime DESC limit 1");
    $sql->execute(array(':userId' => $iduser, ':id' => $id));
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
}
function lastChatMessage() {
    global $pdo;
    global $idc;
    $sql = $pdo->prepare("SELECT c.message_id, c.text as mes, c.author_id as sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE u.idcompany = :companyId order by datetime DESC limit 1");
    $sql->execute(array(':companyId' => $idc));
    $result = $sql->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function numberOfNewMessages($idSender)
{
    global $id;
    $count = DBOnce('COUNT(*)', 'mail', 'recipient='.$id.' AND sender='.$idSender.' AND view_status=0');
    return $count;
}

function getMessages($userId, $interlocutorId)
{
    global $pdo;
    $query = "SELECT message_id, mes, sender, recipient, datetime, view_status FROM `mail` WHERE (`sender` = :userId AND `recipient` = :interlocutorId) OR (`sender` = :interlocutorId AND `recipient` = :userId) ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId, ':interlocutorId' => $interlocutorId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareMessages($result, $userId, $interlocutorId);
    return $result;
}

function getChatMessages()
{
    global $pdo;
    global $id;
    global $idc;
    $query = "SELECT c.message_id, c.text as mes, c.author_id AS sender, c.datetime FROM chat c LEFT JOIN users u ON c.author_id = u.id WHERE u.idcompany = :companyId ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);

    prepareChatMessages($result, $id);
    return $result;
}

function prepareMessages(&$messages, $userId, $interlocutorId)
{
    global $pdo;
    $interlocutorName = fiomess($interlocutorId);
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
            $message['author'] = $interlocutorName;
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
    foreach ($messages as &$message) {
        $message['status'] = '';
        $message['owner'] = false;

        if ($message['sender'] == $userId) {
            $message['owner'] = true;
            $message['author'] = 'Вы';
            $message['status'] = '';
            $message['view_status'] = 1;
        } else {

            $message['status'] = '';
            $message['view_status'] = 1;

            $message['author'] = fiomess($message['sender']);
        }
        $commentType = 'chat';
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
