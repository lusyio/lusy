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
$request = DBOnce('comment, status', 'comments', "idtask=" . $idtask . " and (status = 'returned' or status = 'postpone') order by `datetime` desc");
$messageStatus = DBOnce('status', 'comments', "idtask=" . $idtask . " and (status = 'returned' or status = 'postpone') order by `datetime` desc");
$note = '';
if ($request != false && $messageStatus == 'returned') {
	$request = nl2br($request);
	$note = 'Причина возврата:';
}
?>
