<?php
global $idc;
global $id;
global $pdo;
global $roleu;
global $_buttonInvateNew;
global $tariff;
global $_free;
global $_premium;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}
$firstDay = strtotime(date('Y-m-01'));

$taskDoneManagerQuery = $pdo->prepare("SELECT COUNT(distinct t.id) AS count, t.manager FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE e.company_id = :companyId AND e.action='workdone' AND datetime > :firstDay GROUP BY t.manager");
$taskDoneManagerQuery->execute(array(':companyId' => $idc, ':firstDay' => $firstDay));
$taskDoneManager = $taskDoneManagerQuery->fetchAll(PDO::FETCH_ASSOC);

$doneAsManager = array_column($taskDoneManager, 'count', 'manager');

$taskDoneWorkerQuery = $pdo->prepare("SELECT COUNT(distinct t.id) AS count, t.worker FROM events e LEFT JOIN tasks t ON e.task_id = t.id WHERE e.company_id = :companyId AND e.action='workdone' AND t.worker <> t.manager AND datetime > :firstDay GROUP BY t.worker");
$taskDoneWorkerQuery->execute(array(':companyId' => $idc, ':firstDay' => $firstDay));
$taskDoneWorker = $taskDoneWorkerQuery->fetchAll(PDO::FETCH_ASSOC);

$doneAsWorker = array_column($taskDoneWorker, 'count', 'worker');
$namecompany = DBOnce('idcompany','company','id='.$idc);
$onlineUsers = getOnlineUsersList();

$sql = DB('*','users','idcompany='.$idc . ' ORDER BY is_fired, id');
foreach ($sql as &$user) {
    $user['online'] = false;
    if (in_array($user['id'], $onlineUsers) || $user['activity'] > time() - 180) {
        $user['online'] = true;
    }

    $user['doneAsManager'] = 0;
    if (key_exists($user['id'], $doneAsManager)) {
        $user['doneAsManager'] = $doneAsManager[$user['id']];
    }

    $user['doneAsWorker'] = 0;

    if (key_exists($user['id'], $doneAsWorker)) {
        $user['doneAsWorker'] = $doneAsWorker[$user['id']];
    }

}

$isFiredShown = false;
