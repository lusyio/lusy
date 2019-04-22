<?php
$returndate = date("d.m.Y");
$request = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'returned' order by `datetime` desc");
$request = nl2br($request);
?>