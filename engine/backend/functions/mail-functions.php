<?php

function fiomess($iduser) {
    global $pdo;
    $fio = DBOnce('name','users','id='.$iduser) . ' ' . DBOnce('surname','users','id='.$iduser);
    return $fio;
}

function lastmess($iduser) {
    global $pdo;
    global $id;
    $sql = DB('*','mail','(sender = '.$iduser.' or recipient = '.$iduser.') and (sender = '.$id.' or recipient = '.$id.') order by datetime DESC limit 1');
    foreach ($sql as $n) {
        if ($id == $n['sender']) {
            $author = 'Вы: ';
        } else {
            $author = '';
        }
        echo '<span>' . $author . $n['mes'] . '</span>';
    }
}

function timelastmess($iduser){
    global $pdo;
    global $id;
    $sql = DB('*','mail','(sender = '.$iduser.' or recipient = '.$iduser.') and (sender = '.$id.' or recipient = '.$id.') order by datetime DESC limit 1');
    foreach ($sql as $n) {
        if ($id == $n['sender']) {
            $author = 'Вы: ';
        } else {
            $author = '';
        }
        echo '<span>'. date('d.m H:s', $n['datetime']) . '</span>';
    }
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
