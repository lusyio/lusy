<?php

/**Возвращает список всех файлов, загруженных пользователем
 * @return array [[file_id] - id файла, [file_name] - имя файла, [file_size] - размер файла в байтах,
 * [file_path] - путь к файлу на сервере,[comment_type] тип события, к которому прикреплен файл,
 * [comment_id] - ID омментария,[idtask] - ID комментария или задачи,
 * [author] [surname] [name] - ID, фамилия и имя пользователя, загрузившего файл, [taskName] - имя задачи,
 * [uploadDate] - дата загрузки файла
 */
function getFileList($availableTasks = [0], $isCeo = false)
{
    if ($isCeo) {
        return getCompanyFileList();
    }
    global $id;
    global $idc;
    global $pdo;
    $availableTasksString = implode(', ', $availableTasks);

    $query = "SELECT u.file_id, u.file_name, u.file_size, u.file_path, u.comment_type, u.comment_id, u.cloud,
       IF(c.idtask IS NULL AND u.comment_type='task', u.comment_id, c.idtask) AS idtask, 
       u.author, us.surname, us.name, t.name AS taskName, 
       IF(c.datetime IS NULL, IF(t.datecreate IS NULL, IF(m.datetime IS NULL, ch.datetime ,  m.datetime), t.datecreate), c.datetime) AS uploadDate
FROM `uploads` u
       LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment'
       LEFT JOIN users us ON u.author = us.id
       LEFT JOIN tasks t ON (t.id = u.comment_id AND u.comment_type='task' AND (t.status <> 'planned' OR t.manager = :userId)) OR (t.id = c.idtask AND u.comment_type='comment')
       LEFT JOIN mail m ON m.message_id = u.comment_id AND u.comment_type='conversation'
       LEFT JOIN chat ch ON (u.comment_type='chat' AND ch.message_id = u.comment_id)
WHERE u.company_id = :companyId AND (u.author = :userId OR t.id IN (" . $availableTasksString . ") OR u.comment_type='chat') AND u.is_deleted = 0 ORDER BY uploadDate DESC, u.file_id DESC";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array('userId' => $id,':companyId' => $idc));
    return $dbh->fetchAll(PDO::FETCH_ASSOC);
}

function getFileIdList($availableTasks = [0])
{
    global $id;
    global $idc;
    global $pdo;
    $availableTasksString = implode(', ', $availableTasks);

    $query = "SELECT u.file_id FROM `uploads` u
       LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment'
       LEFT JOIN users us ON u.author = us.id
       LEFT JOIN tasks t ON (t.id = u.comment_id AND u.comment_type='task' AND (t.status <> 'planned' OR t.manager = :userId)) OR (t.id = c.idtask AND u.comment_type='comment')
       LEFT JOIN mail m ON m.message_id = u.comment_id AND u.comment_type='conversation'
WHERE u.company_id = :companyId AND (u.author = :userId OR t.id IN (" . $availableTasksString . ") OR u.comment_type='chat') AND u.is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array('userId' => $id,':companyId' => $idc));
    return $dbh->fetchAll(PDO::FETCH_COLUMN);
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

    $query = "SELECT u.file_id, u.file_name, u.file_size, u.file_path, u.comment_type, u.comment_id, u.cloud,
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

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId AND cloud = 0 AND is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc));
    $result = $dbh->fetchColumn();
    if (is_null($result)) {
        return 0;
    }
    return $result;
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

    $query = "SELECT SUM(file_size) AS 'totalSize' FROM `uploads` WHERE company_id = :companyId AND author = :userId AND cloud = 0 AND is_deleted = 0";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array('userId' => $id, ':companyId' => $idc));
    return $dbh->fetchColumn();
}

/**
 * Устанавливает в БД для файла статус is_deleted = 1 и удаляет файл с сервера, если он не прикреплен из облака
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
    $query = "SELECT file_path, cloud FROM `uploads` WHERE company_id = :companyId AND file_id = :fileId";
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':companyId' => $idc, ':fileId' => $fileId));
    $result = $dbh->fetch(PDO::FETCH_ASSOC);
    if ($result['cloud'] == 0 && realpath($result['file_path'])) {
        unlink($result['file_path']);
    }
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
        if ($file['comment_type'] == 'chat') {
            $file['uploadDate'] = DBOnce('datetime', 'chat', 'message_id = ' . $file['comment_id']);
            $file['comment_id'] = '';
            $file['attachedToLink'] = "/chat/";
        }
        $normalizedFileSize = normalizeSize($file['file_size']);
        $fileSize = $file['file_size'];
        $file['file_size'] = $normalizedFileSize['size'];
        $file['sizeSuffix'] = $normalizedFileSize['suffix'];
        $fileNameParts = explode('.', $file['file_name']);
        $file['extension'] = mb_strtolower(array_pop($fileNameParts));
        $file['date'] = date('d.m.Y', $file['uploadDate']);
    }
}

/**
 * При необходимости конвертирует размер переданный в байтах
 * в нормальный вид (1-1023 Б, 1-1023 КБ, 1-... МБ ли 1-... ГБ)
 * и возвращает массив из элементов ['size']['suffix']
 * @param int $size размер в байтах
 * @return array ['size'] - конвертированное значение ['suffix'] - суффикс/размерность
 */
function normalizeSize($size)
{
    $result = [];
    if ($size >= 1024 * 1024 * 1024) {
        $result['size'] = round($size / (1024 * 1024 * 1024));
        $result['suffix'] = 'ГБ';
    } elseif ($size >= 1024 * 1024) {
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

/**
 * Возвращает максимальный объем хранилища файлов в соответствии с тарифом компании
 * @return float|int Объем хранилища в байтах
 */
function getProvidedStorageSpace()
{
    global $idc;

    $tariff = DBOnce('tariff', 'company', 'id='.$idc);
    if ($tariff == 1) {
        $providedSpace = 1024 * 1024 * 1024;
    } else {
        $providedSpace = 100 * 1024 * 1024;
    }
    return $providedSpace;
}
