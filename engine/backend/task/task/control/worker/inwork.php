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
$note = '';
$request = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'returned' order by `datetime` desc");
if ($request != '') {
	$note = 'Причина возврата:';
}
?>
