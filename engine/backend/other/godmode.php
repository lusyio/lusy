<?php
global $idc;
global $pdo;

if ($idc != 1) {
    header('Location: /');
}

$countCompanies = DBOnce('count(*)','company','');
$countUsers = DBOnce('count(*)','users','');
$countTasks = DBOnce('count(*)','tasks','');
$countComments = DBOnce('count(*)','comments','status="comment"');
$countMail = DBOnce('count(*)','mail','');
$startTime = strtotime(date('Y-m-d'));
$endTime = time();
$eventsCountSql = $pdo->prepare('SELECT COUNT(ALL *) as count, datetime - datetime%(60*60) as period FROM events WHERE datetime between :startTime AND :endTime GROUP BY datetime - datetime%(60*60)');
$eventsCountSql->bindValue(':startTime', (int) $startTime, PDO::PARAM_INT);
$eventsCountSql->bindValue(':endTime', (int) $endTime, PDO::PARAM_INT);
$eventsCountSql->execute();
$eventsCountResult = $eventsCountSql->fetchAll(PDO::FETCH_ASSOC);
$eventsCount = [];
$t = $startTime;
foreach ($eventsCountResult as $count) {
    while ($t < $count['period']) {
        $eventsCount[] = 0;
        $t += 60 * 60;
    }
    $eventsCount[] = (int) $count['count'];
    $t += 60 * 60;
}
while (count($eventsCount) < 24 && $t <= $endTime) {
    $eventsCount[] = 0;
    $t += 60 * 60;
}

$articlesQuery = $pdo->prepare('SELECT article_id, url, language, article_name, article_text, category, description, publish_date FROM blog');
$articlesQuery->execute();
$articlesList = $articlesQuery->fetchAll(PDO::FETCH_ASSOC);
$emailTemplatesDir = 'engine/phpmailer/templates/ru/';
$emailTemplates = scandir($emailTemplatesDir);
