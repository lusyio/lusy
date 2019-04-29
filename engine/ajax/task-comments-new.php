<?php 
$idtask = $_POST['it'];	
$text = $_POST['text'];

$datetime = date("Y-m-d H:i:s");
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
$sql->execute(array(':comment' => $text, ':status' => 'comment', ':iduser' => $id, ':idtask' => $idtask, 'view' => 0, ':datetime' => $datetime));
$idcomment = $pdo->lastInsertId();

if (count($_FILES) > 0) {
    uploadAttachedFiles('comment', $idcomment);
}

/**
 * Загружает каждый файл из массива _FILES в upload/files/
 * и добавляет информацию о нем в бд в таблицу uploads
 * @param string $type Type to which the files is attached: 'task' or 'comment'
 * @param int $id Id of specified type event
 */
function uploadAttachedFiles($type, $id)
{
    global $pdo;
    global $idc;
    $types = ['task', 'comment'];
    if (!in_array($type, $types)) {
        return;
    }

    if ($type == 'comment') {
        global $idtask;
    } else {
        $idtask = $id;
    }

    $dirName = 'upload/files/' . $idtask;
    if (!realpath($dirName)) {
        mkdir($dirName, 0777, true);
    }

    $sql = $pdo->prepare('INSERT INTO `uploads` (file_name, file_size, file_path, comment_id, comment_type, company_id, is_deleted) VALUES (:fileName, :fileSize, :filePath, :commentId, :commentType, :companyId, :isDeleted)');
    foreach ($_FILES as $file) {
        $fileName = basename($file['name']);
        $hashName = md5_file($file['tmp_name']);
        while (file_exists($dirName . '/' . $hashName)) {
            $hashName = md5($hashName);
        }
        $filePath = $dirName . '/' . $hashName;
        $sql->execute(array(':fileName' => $fileName, ':fileSize' => $file['size'], ':filePath' => $filePath, ':commentId' => $id, ':commentType' => $type, ':companyId' => $idc, ':isDeleted' => 0));
        move_uploaded_file($file['tmp_name'], $filePath);
    }
}
?>