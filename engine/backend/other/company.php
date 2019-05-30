<?php
global $idc;
global $id;
global $roleu;

if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

$namecompany = DBOnce('idcompany','company','id='.$idc);
$sql = DB('*','users','idcompany='.$idc . ' ORDER BY is_fired, id');
$isFiredShown = false;
