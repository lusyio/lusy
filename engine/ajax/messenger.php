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
    if (isset($_POST['fromSupport'])) {
        if ($_POST['fromSupport'] == 1 && $idc == 1) {
            $id = 1;
        }
    }
    $mes = link_it($_POST['mes']);
    $mes = filter_var($_POST['mes'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $recipientId = filter_var($_POST['recipientId'], FILTER_SANITIZE_NUMBER_INT);
    $messageTime = time();
    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'],true);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    if (!empty($mes)) {
        $unreadMessagesQuery = $pdo->prepare("SELECT COUNT(DISTINCT message_id) FROM mail WHERE sender = :sender AND recipient = :recipient AND view_status = 0");
        $unreadMessagesQuery->execute([':sender' => $id, ':recipient' => $recipientId]);
        $unreadMessages = $unreadMessagesQuery->fetch(PDO::FETCH_COLUMN);
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

        $supportAdmins = [2, 3, 4];
        $mesData = [
            'senderId' => $id,
            'recipientId' => $recipientId,
            'messageId' => $messageId,
        ];
        $cometData = [];
        if ($id == 1) {
            foreach ($supportAdmins as $admin) {
                $cometData[$admin] = $mesData;
            }
        } elseif ($recipientId == 1) {
            foreach ($supportAdmins as $admin) {
                $cometData[$admin] = $mesData;
                addMailToQueue('sendMessageEmailNotification', [$admin, $id, $messageId], $admin, $messageId);
            }
            $cometData[$id] = $mesData;
        } else {
            $cometData[$recipientId] = $mesData;
            $cometData[$id] = $mesData;
        }
        $cometPdo->multipleSendNewMailMessage($cometData);

        //@sendMessageEmailNotification($recipientId, $id);
        $onlineUsers = getOnlineUsersList();
        $lastRecipientActivity = DBOnce('activity', 'users', 'id = ' . $recipientId);
            $isRecipientOnline = false;
            if (in_array($recipientId, $onlineUsers) || $lastRecipientActivity > time() - 60) {
                $isRecipientOnline = true;
            }
        if ($unreadMessagesQuery == 0 && !$isRecipientOnline) {
            addMailToQueue('sendMessageEmailNotification', [$recipientId, $id, $messageId], $recipientId, $messageId);
        }
        if (!$cometPdo->getStatus()) {
            echo 'reload';
        }
    }
}
if ($_POST['module'] == 'sendMessageToChat') {
    $mes = link_it($_POST['mes']);
    $mes = filter_var($mes, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $messageTime = time();
    $unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
    $googleFiles = [];
    foreach ($unsafeGoogleFiles as $k => $v) {
        $googleFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    $unsafeDropboxFiles = json_decode($_POST['dropboxAttach'],true);
    $dropboxFiles = [];
    foreach ($unsafeDropboxFiles as $k => $v) {
        $dropboxFiles[] = [
            'name' => filter_var($k, FILTER_SANITIZE_STRING),
            'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
            'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
        ];
    }
    if (!empty($mes)) {
        $dbh = "INSERT INTO chat (text, author_id, datetime, company_id) VALUES (:message, :authorId, :datetime, :companyId) ";
        $sql = $pdo->prepare($dbh);
        $sql->execute(array(':message' => $mes, ':authorId' => $id, ':datetime' => $messageTime, ':companyId' => $idc));
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
        $mesData = [
            'messageId' => $messageId,
        ];
        $cometPdo->sendNewChatMessage(getCometTrackChannelName(), $mesData);
        markChatMessageAsRead($messageId);
    }
    if (!$cometPdo->getStatus()) {
        echo 'reload';
    }
}

if ($_POST['module'] == 'updateMessages') {
    if (isset($_POST['fromSupport'])) {
        if ($_POST['fromSupport'] == 1 && $idc == 1) {
            $id = 1;
        }
    }
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $messages = getMessageById($messageId, $id);
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

if ($_POST['module'] == 'sendToAll' && $idc == 1) {
    $messageText = link_it($_POST['message']);
    $messageText = filter_var($messageText, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $messageTime = time();
    $sendTo = filter_var($_POST['sendTo'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $taskCount = filter_var($_POST['taskCount'], FILTER_SANITIZE_NUMBER_INT);

    if (!empty($messageText)) {
        if ($sendTo == 'ceo') {
            sendMessageToAllCeo($messageText, $taskCount);
        }
        if ($sendTo == 'all') {
            sendMessageToAllUsers($messageText);
        }
    }
}
