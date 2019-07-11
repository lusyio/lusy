<?php
require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

// глобальные переменные

global $id;
global $_main;
global $_tasks;
global $_tasknew;
global $_awards;
global $_company;
global $_storage;
global $idc;
global $pdo;
global $roleu;
global $tariff;


// пункты меню для ролей
$menu = [
    'worker' => [
        'main',
        'tasks',
        'newTask',
        'company',
        'storage',
    ],
    'ceo' => [
        'main',
        'tasks',
        'newTask',
        'company',
        'storage',
        'reports'
    ],
];

// количество задач
$worker = DBOnce('COUNT(*)', 'tasks', 'worker=' . $id . ' AND status NOT IN (\'canceled\', \'done\') AND worker <> manager');
$manager = DBOnce('COUNT(*)', 'tasks', 'manager=' . $id . ' AND status NOT IN (\'canceled\', \'done\')');

// файловый прогресс бар
$uploadlimit = 100;
$totalSize = number_format((DBOnce('SUM(file_size) AS totalSize', 'uploads', 'company_id=' . $idc . ' AND is_deleted = 0')) / (1024 * 1024), 0, '', ' ');
$pros = ($totalSize / $uploadlimit) * 100;


$companyTotalFilesSize = getCompanyFilesTotalSize();
$normalizedCompanyFilesSize = normalizeSize($companyTotalFilesSize);

$userTotalFilesSize = getUserFilesTotalSize();
$normalizedUserFilesSize = normalizeSize($userTotalFilesSize);

$providedSpace = 100 * 1024 * 1024;
$normalizedProvidedSpace = normalizeSize($providedSpace);

$companyUsageSpacePercent = round($companyTotalFilesSize * 100 / $providedSpace);
if ($companyUsageSpacePercent == 0 && $companyTotalFilesSize > 0) {
    $companyUsageSpacePercent = 1;
}
$userUsageSpacePercent = round($userTotalFilesSize * 100 / $providedSpace);
if ($userUsageSpacePercent == 0 && $userTotalFilesSize > 0) {
    $userUsageSpacePercent = 1;
}
if ($idc == 1) {
    $currentDatetime = time();
    $activeCompaniesQuery = $pdo->prepare('SELECT COUNT(*) FROM (SELECT count(*) AS eventsCount FROM events WHERE datetime > :datetime group by company_id) as e WHERE eventsCount > :eventsToBeActive');
    $activeCompaniesQuery->bindValue(':datetime', $currentDatetime - (3600 * 24 * 30), PDO::PARAM_INT);
    $activeCompaniesQuery->bindValue(':eventsToBeActive', 5, PDO::PARAM_INT);
    $activeCompaniesQuery->execute();
    $activeCompanies = $activeCompaniesQuery->fetch(PDO::FETCH_COLUMN);
    $countUsers = DBOnce('count(*)','users','');
}