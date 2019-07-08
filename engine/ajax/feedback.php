<?php

global $pdo;
global $id;
global $idc;

if ($_POST['module'] == 'addFeedback') {
    $messageTitle = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $messageText = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $cause = filter_var($_POST['cause'], FILTER_SANITIZE_STRING);
    $page = $_SERVER['HTTP_REFERER'];
    $addFeedbackQuery = $pdo->prepare("INSERT INTO feedback (user_id, message_title, message_text, page_link, cause, datetime) VALUES (:userId, :messageTitle, :messageText, :pageLink, :cause, :datetime)");
    $addFeedbackQuery->execute([':userId' => $id, ':messageTitle' => $messageTitle, ':messageText' => $messageText, ':pageLink' => $page, ':cause' => $cause, ':datetime' => time()]);
}