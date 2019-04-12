<?php 
global $postponedate;
$postponedate = date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)));

?>