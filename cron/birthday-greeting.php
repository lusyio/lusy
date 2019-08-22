<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');
$cron = true;

include __ROOT__ . '/conf.php'; // подключаем базу данных

$langc = 'ru';
include __ROOT__ . '/engine/backend/lang/'.$langc.'.php';

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

$today = date('Y-m-d');
$birthdayUsersQuery = $pdo->prepare("SELECT u.id, u.email, u.name, u.surname, u.idcompany, c.timezone FROM users u LEFT JOIN company c ON u.idcompany = c.id WHERE birthdate = :today AND is_fired = 0");
$birthdayUsersQuery->execute([':today' => $today]);
$birthdayUsers = $birthdayUsersQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($birthdayUsers AS $user) {
    date_default_timezone_set($user['timezone']);
    if (date('H') == 9) {
        // Сообщение в чат
        $message = 'Сегодня день рождения отмечает ' . trim($user['name'] . ' ' . $user['surname']) . '. ПОЗДРАВЛЯЕМ!';
        $addMessageToChatQuery = $pdo->prepare("INSERT INTO chat (text, author_id, datetime, company_id) VALUES (:message, :authorId, :datetime, :companyId)");
        $addMessageToChatQuery->execute(array(':message' => $message, ':authorId' => 1, ':datetime' => time(), ':companyId' => $idc));
        $messageId = $pdo->lastInsertId();
        $mesData = [
            'messageId' => $messageId,
        ];
        $cometPdo->sendNewChatMessage(getCometTrackChannelName($user['idcompany']), $mesData);
        // Сообщение в лог
        $companyUsersQuery = $pdo->prepare("SELECT id FROM users WHERE idcompany = :companyId AND is_fired = 0");
        $companyUsersQuery->execute([':companyId' => $user['idcompany']]);
        $companyUsers = $companyUsersQuery->fetchAll(PDO::FETCH_ASSOC);
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, comment) VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :comment)');
        $cometData = [];
        foreach ($companyUsers as $companyUser) {
            $eventData = [
                ':action' => 'birthday',
                ':taskId' => '',
                ':authorId' => 1,
                ':recipientId' => $companyUser['id'],
                ':companyId' => $user['idcompany'],
                ':datetime' => time(),
                ':comment' => $user['id'],
            ];
            $addEventQuery->execute($eventData);
            $eventId = $pdo->lastInsertId();
            $pushData = [
                'type' => 'comment',
                'eventId' => $eventId,
            ];
            $cometData[$companyUser['id']] = $pushData;
        }
        $cometPdo->multipleSendLogMessage($cometData);
    }
}
