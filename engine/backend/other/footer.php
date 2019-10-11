<?php
global $id;
global $pdo;

$updateAnalyticsQuery = $pdo->prepare("INSERT INTO visits_analytics (user_id, session_id, current_page, previous_page, visit_time, user_agent)
                                            VALUES (:userId, :sessionId, :currentPage, :previousPage, :visitTime, :userAgent)");
if (isset($_SERVER['HTTP_REFERER'])) {
    $fromPage = $_SERVER['HTTP_REFERER'];
    $fromPage = preg_replace('~^[htps\:\/]{0,}localhost~', '', $fromPage);
    $fromPage = preg_replace('~^[htps\:\/]{0,}s.lusy.io~', '', $fromPage);
    $fromPage = preg_replace('~^[htps\:\/]{0,}next.lusy.io~', '', $fromPage);
} else {
    $fromPage = 'неизвестно';
}

$updateAnalyticsQuery->execute([
    ':userId' => $id,
    ':sessionId' => session_id(),
    ':currentPage' => $_SERVER['REQUEST_URI'],
    ':previousPage' => $fromPage,
    ':visitTime' => $_SERVER['REQUEST_TIME'],
    ':userAgent' => (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'неизвестно',
]);

