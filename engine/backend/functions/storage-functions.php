<?php

function getFileList()
{
    global $idc;
    global $pdo;

    $query = "SELECT u.file_id, u.file_name, u.file_size, u.file_path, u.comment_type, u.comment_id, 
       IF(c.idtask IS NULL AND u.comment_type='task', u.comment_id, c.idtask) AS idtask, 
       u.author, us.surname, us.name, t.name AS taskName, 
       IF(c.datetime IS NULL, IF(t.datecreate IS NULL, m.datetime, t.datecreate), c.datetime) AS uploadDate
FROM `uploads` u
       LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment'
       LEFT JOIN users us ON u.author = us.id
       LEFT JOIN tasks t ON (t.id = u.comment_id AND u.comment_type='task') OR (t.id = c.idtask AND u.comment_type='comment')
       LEFT JOIN mail m ON m.message_id = u.comment_id AND u.comment_type='conversation'
WHERE u.company_id = :companyId AND u.is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    return $dbh->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalSize()
{
    global $idc;
    global $pdo;

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId AND is_deleted = 0";
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

function prepareFileList(array &$fileList) {
    foreach ($fileList as &$file) {
        $file['comment_link'] = '';
        if ($file['comment_type'] == 'task') {
            $file['comment_id'] = '';
            $file['attachedToLink'] = "/task/" . $file['idtask']. "/" . $file['comment_link'];
        }
        if ($file['comment_type'] == 'comment') {
            $file['attachedToLink'] = "/task/" . $file['idtask']. "/" . '#' . $file['comment_id'];
        }
        if ($file['comment_type'] == 'conversation') {
            $file['comment_link'] = '#' . $file['comment_id'];
            $fromTo = DB('sender, recipient', 'mail', 'message_id=' . $file['comment_id']);
            $file['attachedToLink'] = '';
        }
        $fileSize = $file['file_size'];
        $file['sizeSuffix'] = 'Б';
        if ($file['file_size'] > 1024 * 1024) {
            $file['file_size'] = round($fileSize / (1024 * 1024));
            $file['sizeSuffix'] = 'МБ';
        } elseif ($fileSize > 1024) {
            $file['file_size'] = round($fileSize / 1024);
            $file['sizeSuffix'] = 'КБ';
        }
        $fileNameParts = explode('.', $file['file_name']);
        $file['extension'] = mb_strtolower(array_pop($fileNameParts));
        $file['date'] = date('d.m.Y', strtotime($file['uploadDate']));
    }
}
