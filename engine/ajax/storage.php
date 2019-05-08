<?php
global $pdo;
global $datetime;
global $id;
global $idc;

require_once 'engine/backend/functions/storage-functions.php';

if ($_POST['module'] == 'deleteFile') {
    $fileId = filter_var($_POST['fileId'], FILTER_SANITIZE_NUMBER_INT);
    removeFile($fileId);
}