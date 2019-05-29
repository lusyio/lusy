<?php

/**Возвращает список всех файлов, загруженных пользователем
 * @return array [[file_id] - id файла, [file_name] - имя файла, [file_size] - размер файла в байтах,
 * [file_path] - путь к файлу на сервере,[comment_type] тип события, к которому прикреплен файл,
 * [comment_id] - ID омментария,[idtask] - ID комментария или задачи,
 * [author] [surname] [name] - ID, фамилия и имя пользователя, загрузившего файл, [taskName] - имя задачи,
 * [uploadDate] - дата загрузки файла
 */
function getFileList()
{
    global $id;
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
WHERE u.company_id = :companyId AND u.author = :userId AND u.is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array('userId' => $id,':companyId' => $idc));
    return $dbh->fetchAll(PDO::FETCH_ASSOC);
}

/**Возвращает список всех файлов, загруженных пользователем
 * @return array [[file_id] - id файла, [file_name] - имя файла, [file_size] - размер файла в байтах,
 * [file_path] - путь к файлу на сервере,[comment_type] тип события, к которому прикреплен файл,
 * [comment_id] - ID омментария,[idtask] - ID комментария или задачи,
 * [author] [surname] [name] - ID, фамилия и имя пользователя, загрузившего файл, [taskName] - имя задачи,
 * [uploadDate] - дата загрузки файла
 */
function getCompanyFileList()
{
    global $id;
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

/**
 * Возвращает суммарный объем всех файлов, загруженных сотрудниками отдельной компании, в байтах
 * @return mixed
 */
function getCompanyFilesTotalSize()
{
    global $idc;
    global $pdo;

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId AND is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    return $dbh->fetchColumn();
}

/**
 * Возвращает суммарный объем всех файлов, загруженных отдельным сотрудником компании, в байтах
 * @return mixed
 */
function getUserFilesTotalSize()
{
    global $id;
    global $idc;
    global $pdo;

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId AND author = :userId AND is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array('userId' => $id, ':companyId' => $idc));
    return $dbh->fetchColumn();
}

/**
 * Устанавливает в БД для файла статус is_deleted = 1 и удаляет файл с сервера
 * @param $fileId
 */
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

/**
 * Добавляет к выходному массиву из getFileList() элементы:
 * ['attachedToLink'] - если файл прикреплен к:
 *      задаче - ссылка на задачу,
 *      комментарию - ссылка на комментарий в задаче,
 *      диалогу - пустая строка,
 *['file_size'] - размер файла - мантисса
 *['sizeSuffix'] - множитель размера
 *['extension'] - расширение файла
 * ['date'] - дата загрузки в формате ДД.ММ.ГГГГ
 * @param array $fileList
 */
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
        $normalizedFileSize = normalizeSize($file['file_size']);
        $fileSize = $file['file_size'];
        $file['file_size'] = $normalizedFileSize['size'];
        $file['sizeSuffix'] = $normalizedFileSize['suffix'];
        $fileNameParts = explode('.', $file['file_name']);
        $file['extension'] = mb_strtolower(array_pop($fileNameParts));
        $file['date'] = date('d.m.Y', strtotime($file['uploadDate']));
    }
}

/**
 * При необходимости конвертирует размер переданный в байтах
 * в нормальный вид (1-1023 Б, 1-1023 КБ или 1-... МБ)
 * и возвращает массив из элементов ['size']['suffix']
 * @param int $size размер в байтах
 * @return array ['size'] - конвертированное значение ['suffix'] - суффикс/размерность
 */
function normalizeSize($size)
{
    $result = [];
    if ($size >= 1024 * 1024) {
        $result['size'] = round($size / (1024 * 1024));
        $result['suffix'] = 'МБ';
    } elseif ($size >= 1024) {
        $result['size'] = round($size / 1024);
        $result['suffix'] = 'КБ';
    } else {
        $result['size'] = $size;
        $result['suffix'] = 'Б';
    }
    return $result;
}

