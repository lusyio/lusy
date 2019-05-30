<?php
global $id;
global $cometHash;

require_once 'engine/backend/functions/mail-functions.php';

$recipientId = filter_var($_GET['mail'], FILTER_SANITIZE_NUMBER_INT);

$messages = getMessages($id, $recipientId);
setMessagesViewStatus($id, $recipientId);
