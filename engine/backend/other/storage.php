<?php

global $id;

require_once 'engine/backend/functions/storage-functions.php';

$companyTotalFilesSize = getCompanyFilesTotalSize();
$normalizedCompanyFilesSize = normalizeSize($companyTotalFilesSize);

$userTotalFilesSize = getUserFilesTotalSize();
$normalizedUserFilesSize = normalizeSize($userTotalFilesSize);

$providedSpace = 100 * 1024 * 1024;
$normalizedProvidedSpace = normalizeSize($providedSpace);

$companyUsageSpacePercent = round($companyTotalFilesSize * 100 / $providedSpace);
$userUsageSpacePercent = round($userTotalFilesSize * 100 / $providedSpace);

$fileList = getFileList();
prepareFileList($fileList);

