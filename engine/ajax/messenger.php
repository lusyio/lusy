<?php

global $pdo;
global $cometPdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';

if ($_POST['module'] == 'sendMessage') {
    $mes = link_it($_POST['mes']);
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $recipientId = filter_var($_POST['recipientId'], FILTER_SANITIZE_NUMBER_INT);
    $messageTime = time();
    $unsafeGoogleFiles = json_decode($_POST['googleAttach']);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach']);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    if (!empty($mes)) {
        $dbh = "INSERT INTO mail (mes, sender, recipient, datetime) VALUES (:mes, :sender, :recipient, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array('mes' => $mes, 'sender' => $id, 'recipient' => $recipientId, 'datetime' => $messageTime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            echo 'has file';
            uploadAttachedFiles('conversation', $messageId);
        }
        if (count($googleFiles) > 0) {
            addGoogleFiles('conversation', $messageId, $googleFiles);
        }
        if (count($dropboxFiles) > 0) {
            addDropboxFiles('conversation', $messageId, $dropboxFiles);
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
        //@sendMessageEmailNotification($recipientId, $id);
        addMailToQueue('sendMessageEmailNotification', [$recipientId, $id], $recipientId, $messageId);
    }
}
if ($_POST['module'] == 'sendMessageToChat') {
    $mes = link_it($_POST['mes']);
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $messageTime = time();
    $unsafeGoogleFiles = json_decode($_POST['googleAttach']);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach']);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    if (!empty($mes)) {
        $dbh = "INSERT INTO chat (text, author_id, datetime) VALUES (:message, :authorId, :datetime) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array(':message' => $mes, ':authorId' => $id, ':datetime' => $messageTime));
        $messageId = $pdo->lastInsertId();
        if (count($_FILES) > 0) {
            uploadAttachedFiles('chat', $messageId);
        }
        if (count($googleFiles) > 0) {
            addGoogleFiles('chat', $messageId, $googleFiles);
        }
        if (count($dropboxFiles) > 0) {
            addDropboxFiles('chat', $messageId, $dropboxFiles);
        }

        $cometSql = $cometPdo->prepare("INSERT INTO pipes_messages (name, event, message) VALUES (:channelName, 'newChat', :jsonMesData)");
        $messageCometForSender = "<p>Вы (" . $datetime . "):</p><p>" . $mes . "</p>";
        $messageCometForRecipient = "<p>" . fiomess($id) . " (" . $datetime . "):</p><p>" . $mes . "</p>";
        $mesData = [
            'messageId' => $messageId,
        ];
        $jsonMesData = json_encode($mesData);
        $cometSql->execute(array(':channelName' => getCometTrackChannelName(), ':jsonMesData' => $jsonMesData));
        markChatMessageAsRead($messageId);
    }
}

if ($_POST['module'] == 'updateMessages') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getMessageById($messageId);
    foreach ($messages as $message) {
        include __ROOT__ . '/engine/frontend/other/message.php';
    }
}
if ($_POST['module'] == 'updateChat') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getChatMessageById($messageId);
    $isCeoAndInChat = $roleu == 'ceo';
    foreach ($messages as $message) {
        include __ROOT__ . '/engine/frontend/other/message.php';
    }
}

if ($_POST['module'] == 'markMessageAsRead') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    markMessageAsRead($messageId);
}
if ($_POST['module'] == 'markChatMessageAsRead') {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    markChatMessageAsRead($messageId);
}

if ($_POST['module'] == 'deleteMessage') {
    if ($roleu == 'ceo') {
        $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
        deleteMessageFromChat($messageId);
    }
}
