<?php
global $idc;
$namecompany = DBOnce('idcompany','company','id='.$idc);
$sql = DB('*','users','idcompany='.$idc.' and role="worker" order by points desc');