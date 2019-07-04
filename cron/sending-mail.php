<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('__ROOT__', __DIR__ . '/../');

include __ROOT__ . '/conf.php'; // подключаем базу данных
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/task-functions.php';

$mailQueueQuery = $pdo->prepare("SELECT mq.queue_id, mq.function_name, mq.args, mq.start_time, mq.user_id, c.timezone FROM mail_queue mq LEFT JOIN users u ON mq.user_id = u.id LEFT JOIN company c ON u.idcompany = c.id");
$mailQueueQuery->execute();
$mailQueue = $mailQueueQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($mailQueue as $mail) {
    date_default_timezone_set($mail['timezone']);
    $notifications = getNotificationSettings($mail['user_id']);
    if ($notifications['silence_start'] != -1) {
        $currentHour = date('G');
        $isCurrentHourBetween = $currentHour >= $notifications['silence_start'] && $currentHour < $notifications['silence_end'];
        $isSilenceOverMidnight = $notifications['silence_start'] > $notifications['silence_end'];
        if ($isSilenceOverMidnight) {
            $isNowSilence = $currentHour >= $notifications['silence_start'] || $currentHour < $notifications['silence_end'];
        } else {
            $isNowSilence = $currentHour >= $notifications['silence_start'] && $currentHour < $notifications['silence_end'];
        }
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
