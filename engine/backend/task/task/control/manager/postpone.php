<?php 

$postponedate = date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)));
$request = DBOnce('comment', 'comments', "idtask=" . $idtask . " and status = 'request' order by `datetime` desc");

?>