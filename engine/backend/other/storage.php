<?php

function getFileList()
{
    global $idc;
    global $pdo;

    $query = "SELECT u.file_id, u.file_name, u.file_size, u.file_path, u.comment_type, u.comment_id, c.idtask FROM `uploads` u LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment' WHERE u.company_id = :companyId AND u.is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    return $dbh->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalSize()
{
    global $idc;
    global $pdo;

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    return $dbh->fetchColumn();
}

function removeFile($fileId) {
    global $idc;
    global $pdo;
    // устанавливаем для файла статус is_deleted 1
    $fileId = filter_var($fileId, FILTER_SANITIZE_NUMBER_INT);
    $query = "UPDATE `uploads` SET is_deleted = 1 WHERE company_id = :companyId AND file_id = :fileId";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc, ':fileId' => $fileId));
    // удаляем файл из upload/files
    $query = "SELECT file_path FROM `uploads` WHERE company_id = :companyId AND file_id = :fileId";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc, ':fileId' => $fileId));
    $filePath = $dbh->fetchColumn();
    unlink($filePath);
}


$totalSize = getTotalSize();
$fileList = getFileList();
prepareFileList($fileList);

function prepareFileList(array &$fileList) {
    foreach ($fileList as &$file) {
        $file['comment_link'] = '';
        if ($file['comment_type'] == 'task') {
            $file['idtask'] = $file['comment_id'];
            $file['comment_id'] = '';
        }
        if ($file['comment_type'] == 'comment') {
            $file['comment_link'] = '#' . $file['comment_id'];
        }
    }
}