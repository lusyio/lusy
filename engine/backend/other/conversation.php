<?php
global $pdo;
global $datetime;
global $id;
global $idc;

$recipientId = $_GET['mail'];

if (!empty($_POST['mes'])) {
    $dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
    $sql = $pdo->prepare($dbh);
    $sql->execute(array('mes' => $_POST['mes'], 'sender' => $id, 'recipient' => $recipientId, 'datetime' => $datetime));

    if ($sql) {
        echo 'Отправлено';
    }
}

$messages = getMessages($id, $recipientId);

function getMessages($userId, $interlocutorId)
{
    global $pdo;
    $query = "SELECT message_id, mes, sender, recipient, datetime FROM `mail` WHERE `sender` IN (:userId, :interlocutorId) OR `recipient` IN (:userId, :interlocutorId) ORDER BY `datetime`";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':userId' => $userId, ':interlocutorId' => $interlocutorId));
    $result = $dbh->fetchAll(PDO::FETCH_ASSOC);
    prepareMessages($result, $userId, $interlocutorId);
    return $result;
}

function prepareMessages(&$messages, $userId, $interlocutorId)
{
    $interlocutorName = fiomess($interlocutorId);
    foreach ($messages as &$message) {
        if ($message['sender'] == $userId) {
            $message['author'] = 'Вы';
        } else {
            $message['author'] = $interlocutorName;
        }
    }
}

function fiomess($iduser)
{
    global $pdo;
    $fio = DBOnce('name', 'users', 'id=' . $iduser) . ' ' . DBOnce('surname', 'users', 'id=' . $iduser);
    return $fio;
}