<?php
global $id;
global $idc;
global $pdo;
global $cometPdo;
if (isset($_GET['access_token'])) {
    $yandexToken = $_GET['access_token'];
//    $updateYandexTokenQuery = $pdo->prepare('UPDATE users SET yandex_token = :yandexToken WHERE id = :userId');
//    $updateYandexTokenQuery->execute(array(':yandexToken' => $yandexToken, ':userId' => $id));

    $cometSql = $cometPdo->prepare("INSERT INTO `users_messages` (id, event, message) VALUES (:id, 'yandexToken', :token)");
    $cometSql->execute(array(':id' => $id, ':token' => $yandexToken));
}
