<?php

global $id;
global $cometHash;
global $cometTrackChannelName;
global $roleu;

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
if ($roleu == 'ceo') {
    $fileList = getCompanyFileList();
} else {
    $fileList = getFileList();
}
prepareFileList($fileList);

