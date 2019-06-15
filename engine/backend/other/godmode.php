<?php
global $pdo;
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

