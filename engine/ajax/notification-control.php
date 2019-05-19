<?php
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
