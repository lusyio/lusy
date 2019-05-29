<?php
global $pdo;
global $datetime;
global $id;
global $idc;
global $roleu;

require_once 'engine/backend/functions/storage-functions.php';

if ($_POST['module'] == 'deleteFile') {
    $fileId = filter_var($_POST['fileId'], FILTER_SANITIZE_NUMBER_INT);
    $author = DBOnce('author', 'uploads', 'file_id='.$fileId);
    if ($author == $id || $roleu == 'ceo') {
        removeFile($fileId);
    }
}