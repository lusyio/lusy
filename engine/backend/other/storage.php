<?php

global $id;
global $idc;
global $cometHash;
global $cometTrackChannelName;
global $roleu;
global $supportCometHash;

require_once __ROOT__ . '/engine/backend/functions/storage-functions.php';

$companyTotalFilesSize = getCompanyFilesTotalSize();
$normalizedCompanyFilesSize = normalizeSize($companyTotalFilesSize);

$userTotalFilesSize = getUserFilesTotalSize();
$normalizedUserFilesSize = normalizeSize($userTotalFilesSize);

$providedSpace = getProvidedStorageSpace();
$normalizedProvidedSpace = normalizeSize($providedSpace);

$companyUsageSpacePercent = round($companyTotalFilesSize * 100 / $providedSpace);
if ($companyUsageSpacePercent == 0 && $companyTotalFilesSize > 0) {
    $companyUsageSpacePercent = 1;
}
$userUsageSpacePercent = round($userTotalFilesSize * 100 / $providedSpace);
if ($userUsageSpacePercent == 0 && $userTotalFilesSize > 0) {
    $userUsageSpacePercent = 1;
}
require_once __ROOT__ . '/engine/backend/classes/TaskListStrategy.php';
$AvailableTasksList = TaskListStrategy::getAvailableTasks($id, $idc, $roleu == 'ceo');
$fileList = getFileList($AvailableTasksList);
prepareFileList($fileList);

