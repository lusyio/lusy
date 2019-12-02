<?php
if ($dayost == 0) {
	$text = $GLOBALS["_deadline"];
	$head = $GLOBALS["_lastday"];
} 
if ($dayost > 0) {
	$text = $GLOBALS["_dayost"];
	$head = $dayost . ' ' . $GLOBALS["_days"];
}
if ($dayost < 0) {
	$text = $GLOBALS["_endfast"];
	$head = $GLOBALS["_overdue"];
}
$request = DBOnce('comment', 'comments', "idtask=" . $idtask . " and (status = 'returned' or status = 'postpone') order by `datetime` desc");
$messageStatus = DBOnce('status', 'comments', "idtask=" . $idtask . " and (status = 'returned' or status = 'postpone') order by `datetime` desc");
$commentId= DBOnce('id', 'comments', "idtask=" . $idtask . " and (status = 'returned' or status = 'postpone') order by `datetime` desc");
$note = '';
$displayNote = 'd-none';
$showNote = false;
if ($request != false && $messageStatus == 'returned') {
	$note = 'Причина возврата:';
	$displayNote = '';
	$showNote = true;
	$filesQuery = $pdo->prepare('SELECT file_id, file_name, file_size, file_path, comment_id, cloud, is_deleted FROM uploads WHERE comment_id = :commentId and comment_type = :commentType');
	$filesQuery->execute(array(':commentId' => $commentId, ':commentType' => 'comment'));
	$files = $filesQuery->fetchAll(PDO::FETCH_ASSOC);
	foreach ($files as $key => $file) {
		$fileNameParts = explode('.', $file['file_name']);
		$files[$key]['extension'] = mb_strtolower(array_pop($fileNameParts));
	}

}
$request = nl2br($request);
?>
