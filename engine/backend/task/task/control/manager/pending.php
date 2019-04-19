<?php
$pendingdate = date("d.m.Y");
$report = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
?>
