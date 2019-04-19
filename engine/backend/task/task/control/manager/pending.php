<?php
$report = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'report' order by `datetime` desc");
$pendingdate=date("d.m.Y");
?>
