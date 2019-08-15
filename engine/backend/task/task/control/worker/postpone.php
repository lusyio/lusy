<?php
$statusWithDate = DBOnce('status', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
$requestedDate = preg_split('~:~', $statusWithDate)[1];
$postponedate = date("d.m", $requestedDate);
$request = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status like 'request%' order by `datetime` desc");
$request = nl2br($request);
?>
