<?php

require_once 'engine/backend/functions/storage-functions.php';

$totalSize = getTotalSize();
$totalSuffix = 'Б';
if ($totalSize > 1024 * 1024) {
    $totalSize = round($totalSize / (1024 * 1024));
    $totalSuffix = 'МБ';
} elseif ($totalSize > 1024) {
    $totalSize = round($totalSize / 1024);
    $totalSuffix = 'КБ';
}
$fileList = getFileList();
prepareFileList($fileList);
