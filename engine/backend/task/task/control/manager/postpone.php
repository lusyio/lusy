<?php 

$postponedate = date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)));
$request = DBOnce('comment', 'comments', 'idtask=' . $idtask . ' order by `datetime` desc');

?>