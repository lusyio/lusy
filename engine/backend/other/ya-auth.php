<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
if (isset($_GET['access_token'])) {
    $yandexToken = $_GET['access_token'];
    session_start();
    $_SESSION['yaToken'] = $yandexToken;
    $cometSql = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'yandexToken', :token)");
    $cometSql->execute(array(':id' => $id, ':token' => $yandexToken));
}
