<?php
$idcom = str_replace("#", "", $_POST['ic']);

$query = "SELECT file_id, file_path FROM `uploads` WHERE comment_id = :commentId and comment_type = 'comment'";
$dbh = $pdo->prepare($query);
$dbh->execute(array(':commentId' => $idcom));
$files = $dbh->fetchAll(PDO::FETCH_ASSOC);
if (count($files) > 0) {
    $deleteQuery = 'DELETE FROM `uploads` WHERE file_id = :fileId';
    $deleteDbh = $pdo->prepare($deleteQuery);
    foreach ($files as $file) {
        unlink($file['file_path']);
        $deleteDbh->execute(array(':fileId' => $file['file_id']));
    }
}
$sql = "DELETE from comments where id='".$idcom."'";
$sql = $pdo->prepare($sql);
$sql->execute();
?>