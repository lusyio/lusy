<?php 
$pendingdate = date("d.m.Y");
$report = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
$commentId = DBOnce('id', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
$report = nl2br($report);
$filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, cloud, is_deleted FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
$filesQuery->execute(array(':commentId' => $commentId, ':commentType' => 'comment'));
$files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
foreach ($files as $key => $file) {
    $fileNameParts = explode('.', $file['file_name']);
    $files[$key]['extension'] = mb_strtolower(array_pop($fileNameParts));
}
?>
