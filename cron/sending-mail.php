<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include __DIR__ . '/../conf.php'; // подключаем базу данных
require_once __DIR__ . '/../engine/backend/functions/common-functions.php';
require_once __DIR__ . '/../engine/backend/functions/task-functions.php';

$mailQueueQuery = $pdo->prepare("SELECT queue_id, function_name, args, start_time FROM mail_queue");
$mailQueueQuery->execute();
$mailQueue = $mailQueueQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($mailQueue as $mail) {
    $notifications = getNotificationSettings($mail['user_id']);
    if ($notifications['silence_start'] != -1) {
        $currentHour = date('G');
        $isSilenceOverMidnight = $notifications['silence_start'] > $notifications['silence_end'];
        $isCurrentHourBetween = $currentHour >= $notifications['silence_start'] && $currentHour < $notifications['silence_end'];
        $isNowSilence = ($isSilenceOverMidnight && !$isCurrentHourBetween) || (!$isSilenceOverMidnight && $isCurrentHourBetween);
        if ($isNowSilence) {
            continue;
        }
    }

    if (function_exists($mail['function_name'])) {
        $arguments = json_decode($mail['args']);
        call_user_func_array($mail['function_name'], $arguments);
        removeMailFromQueue($mail['queue_id']);
    }
}
