<?php
global $pdo;
global $cometPdo;
global $datetime;
global $id;
global $idc;

// обновление таблицы авторизации на комет-сервере
$hash = md5($id . 'salt-pepper');
$cometQuery = "INSERT INTO users_auth (id, hash )VALUES (:id, :hash)";
$cometSql = $cometPdo->prepare($cometQuery);
$cometSql->execute(array(':id' => $id, ':hash' => $hash));

$recipientId = $_GET['mail'];

$messages = getMessages($id, $recipientId);

function getMessages($userId, $interlocutorId)
{
    global $pdo;
    $query = "SELECT message_id, mes, sender, recipient, datetime FROM `mail` WHERE (`sender` = :userId AND `recipient` = :interlocutorId) OR (`sender` = :interlocutorId AND `recipient` = :userId) ORDER BY `datetime`";
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
        if ($message['sender'] == $userId) {
            $message['author'] = 'Вы';
        } else {
            $message['author'] = $interlocutorName;
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

function fiomess($iduser)
{
    global $pdo;
    $fio = DBOnce('name', 'users', 'id=' . $iduser) . ' ' . DBOnce('surname', 'users', 'id=' . $iduser);
    return $fio;
}