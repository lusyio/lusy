<?php 

$postponedate = date("d.m", strtotime(DBOnce('datepostpone','tasks','id='.$idtask)));


?>