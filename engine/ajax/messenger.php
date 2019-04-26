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