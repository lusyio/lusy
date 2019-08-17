<?php
global $idc;
global $pdo;

if ($idc != 1) {
    header('Location: /');
}

$countCompanies = DBOnce('count(*)','company','');
$now = timestamp(date("Y-m-d 00:00:00"));
$countCompaniesRegToday = DBOnce('count(*)','company','datareg > '.$now);
$companyRegsDays = [];
for ($i = 1; $i <= 7; $i++) {
    $dateShow = date("d.m",strtotime("-$i day"));
    $newdayStart = timestamp(date("Y-m-d 00:00:00",strtotime("-$i day")));
    $newdayEnd = timestamp(date("Y-m-d 23:59:59",strtotime("-$i day")));
    $countCompaniesReg = DBOnce('count(*)','company','datareg > '.$newdayStart.' and datareg < '.$newdayEnd);
    array_push($companyRegsDays, ['date' => $dateShow,'count' => $countCompaniesReg, ]);
}
$countUsers = DBOnce('count(*)','users','');
$countTasks = DBOnce('count(*)','tasks','');
$countComments = DBOnce('count(*)','comments','status="comment"');
$countMail = DBOnce('count(*)','mail','');
$currentDatetime = time();
$activeCompaniesQuery = $pdo->prepare('SELECT COUNT(*) FROM (SELECT count(*) AS eventsCount FROM events WHERE datetime > :datetime group by company_id) as e WHERE eventsCount > :eventsToBeActive');
$activeCompaniesQuery->bindValue(':datetime', $currentDatetime - (3600 * 24 * 30), PDO::PARAM_INT);
$activeCompaniesQuery->bindValue(':eventsToBeActive', 5, PDO::PARAM_INT);
$activeCompaniesQuery->execute();
$activeCompanies = $activeCompaniesQuery->fetch(PDO::FETCH_COLUMN);

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
$emailTemplatesDir = __ROOT__ . '/engine/phpmailer/templates/ru/';
$emailTemplates = scandir($emailTemplatesDir);

$feedbackQuery = $pdo->prepare("SELECT f.message_id, f.user_id, f.message_title, f.message_text, f.page_link, f.datetime, f.cause, u.name, u.surname, c.idcompany, c.id AS company_id FROM feedback f LEFT JOIN users u ON f.user_id = u.id LEFT JOIN company c ON u.idcompany = c.id");
$feedbackQuery->execute();
$feedback = $feedbackQuery->fetchAll(PDO::FETCH_ASSOC);

$promocodesQuery = $pdo->prepare("SELECT promocode_id, promocode_name, days_to_add, is_multiple, valid_until, used FROM promocodes");
$promocodesQuery->execute();
$promocodes = $promocodesQuery->fetchAll(PDO::FETCH_ASSOC);

$companiesListQuery = $pdo->prepare("SELECT id, idcompany, full_company_name FROM company");
$companiesListQuery->execute();
$companiesList = $companiesListQuery->fetchAll(PDO::FETCH_ASSOC);

$companiesInfoQuery = $pdo->prepare("SELECT c.id, c.idcompany, c.tariff, c.lang, c.full_company_name, c.site, c.description, c.datareg,
       (SELECT COUNT(*) FROM tasks t WHERE t.idcompany = c.id) AS allTasks,
       (SELECT COUNT(*) FROM tasks t WHERE t.idcompany = c.id AND t.status NOT IN ('canceled', 'done')) AS activeTasks,
       (SELECT COUNT(*) FROM users u WHERE u.idcompany = c.id AND u.is_fired = 0) AS activeUsers
FROM company c ORDER BY datareg DESC");
$companiesInfoQuery->execute();
$companiesInfo = $companiesInfoQuery->fetchAll(PDO::FETCH_ASSOC);

function timestamp($date) {
    $timestamp = strtotime($date);
    return $timestamp;
}