<?php
global $id;
global $pdo;
if($_POST['module'] == 'checkNew') {
    $title = 'test title 1';
    $messageText = 'some text for testing 1';
    $response = [];
    $response[] = include 'engine/frontend/other/push-message.php';
    $title = 'test title 2';
    $messageText = 'some text for testing 2';
    $response[] = include 'engine/frontend/other/push-message.php';
    echo json_encode($response);
}
if($_POST['module'] == 'newMessage' && isset($_POST['messageId'])) {
    $messageId = filter_var($_POST['messageId'], FILTER_SANITIZE_NUMBER_INT);
    $lastMessageQuery = $pdo->prepare('SELECT m.sender, m.mes, u.name, u.surname FROM mail m LEFT JOIN users u on m.sender = u.id WHERE m.recipient = :userId and message_id = :messageId ORDER BY m.datetime DESC LIMIT 1');
    $queryData = [
        ':userId' => $id,
        ':messageId' => $messageId,
    ];
    $lastMessageQuery->execute($queryData);

    $message = $lastMessageQuery->fetch(PDO::FETCH_ASSOC);
    $title = $message['name'] . ' ' . $message['surname'];
    $messageText = $message['mes'];
    $response = [];
    $response[] = include 'engine/frontend/other/push-message.php';
    echo json_encode($response);
}
if($_POST['module'] == 'newTask' && isset($_POST['taskId'])) {
    $taskId = filter_var($_POST['taskId'], FILTER_SANITIZE_NUMBER_INT);
    $title = 'Новая задача';
    $messageText = '<a href="/../task/' . $taskId . '/">Перейти к задаче</a>';
    $response = [];
    $response[] = include 'engine/frontend/other/push-message.php';
    echo json_encode($response);
}
