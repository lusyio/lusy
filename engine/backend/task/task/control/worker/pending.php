<?php 
$pendingdate = date("d.m.Y");
$report = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
$commentId = DBOnce('id', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
$report = nl2br($report);
$filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, cloud FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
$filesQuery->execute(array(':commentId' => $commentId, ':commentType' => 'task'));
$files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
?>
