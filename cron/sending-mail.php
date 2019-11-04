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
require_once __ROOT__ . '/engine/backend/functions/task-functions.php';

// получаем из базы список всех писем к отправке
$mailQueueQuery = $pdo->prepare("SELECT mq.queue_id, mq.function_name, mq.args, mq.start_time, mq.user_id, c.timezone, mq.event_id FROM mail_queue mq LEFT JOIN users u ON mq.user_id = u.id LEFT JOIN company c ON u.idcompany = c.id AND mq.user_id > 1");
$mailQueueQuery->execute();
$mailQueue = $mailQueueQuery->fetchAll(PDO::FETCH_ASSOC);

// Проходим по всему списку писем
foreach ($mailQueue as $key => $mail) {
    if (is_null($mail['user_id']) || $mail['user_id'] == 1) {
        removeMailFromQueue($mail['queue_id']);
        unset($mailQueue[$key]);
        continue;
    }

    // Безусловно отправляем письмо с ссылкой для активации и удаляем его из списка и базы
    if ($mail['function_name'] == 'sendActivationLink') {
        if (function_exists($mail['function_name'])) {
            $arguments = json_decode($mail['args']);
            call_user_func_array($mail['function_name'], $arguments);
            removeMailFromQueue($mail['queue_id']);
            unset($mailQueue[$key]);
            continue;
        }
    }
    // Удаляем из списка и базы письма об уже прочитанных личных сообщениях
    $isMessage = $mail['function_name'] == 'sendMessageEmailNotification';
    $isRead = checkViewStatus($mail['event_id'], $isMessage);
    if ($isRead) {
        removeMailFromQueue($mail['queue_id']);
        unset($mailQueue[$key]);
        continue;
    }
    // Устанавливаем часовой пояс пользователя
    date_default_timezone_set($mail['timezone']);
    // Получаем настройки уведомлений пользователя
    $notifications = getNotificationSettings($mail['user_id']);

    // Пропускаем письма, если сейчас у пользователя режим "не беспокоить"
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

    // Удаляем из списка и базы письма, связанные с задачами, если пользователь отключил уведомления о них
    if ($mail['function_name'] == 'sendTaskWorkerEmailNotification' || $mail['function_name'] == 'sendTaskManagerEmailNotification') {
        $arguments = json_decode($mail['args']);
        if (
            ($arguments[1] == 'createtask' && !$notifications['task_create']) ||
            ($arguments[1] == 'overdue' && !$notifications['task_overdue']) ||
            ($arguments[1] == 'review' && !$notifications['task_review']) ||
            ($arguments[1] == 'postpone' && !$notifications['task_postpone'])
        ) {
            removeMailFromQueue($mail['queue_id']);
            unset($mailQueue[$key]);
        }
    }

    // Удаляем из списка и базы письма, связанные с достижениями, комментариями или личными сообщениями,
    // если пользователь отключил уведомления о них
    if (
        ($mail['function_name'] == 'sendAchievementEmailNotification' && !$notifications['achievement']) ||
        ($mail['function_name'] == 'sendCommentEmailNotification' && !$notifications['comment']) ||
        ($mail['function_name'] == 'sendMessageEmailNotification' && !$notifications['message']) ||
        (in_array($mail['function_name'], [
                'sendSubscribeProlongationFailedEmailNotification',
                'sendSubscribePremiumEmailNotification',
                'sendSubscribePromoEmailNotification',
            ]) && !$notifications['payment'])
    ) {
        removeMailFromQueue($mail['queue_id']);
        unset($mailQueue[$key]);
    }
}

// Создаем карту пользователь - число писем
$multipleEvents = [];
foreach ($mailQueue as $key => $mail) {
    if (isset($multipleEvents[$mail['user_id']])) {
        $multipleEvents[$mail['user_id']]++;
    } else {
        $multipleEvents[$mail['user_id']] = 1;
    }
}

// Отправляем письма пользователям, у которых одно событие
foreach ($mailQueue as $key => $mail) {
    if ($multipleEvents[$mail['user_id']] == 1 && $mail['user_id'] > 1) {
        if (function_exists($mail['function_name'])) {
            $arguments = json_decode($mail['args']);
            call_user_func_array($mail['function_name'], $arguments);
            removeMailFromQueue($mail['queue_id']);
        }
    }
}

// Отправляем письма пользователям, у которых несколько событий
foreach ($multipleEvents as $userId => $eventCount) {
    if ($eventCount > 1 && $userId > 1) {
        sendMultipleEventsEmailNotification($userId);
        removeMailFromQueueByUserId($userId);
    }
}
