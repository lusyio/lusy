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
$onlineUsers = getOnlineUsersList();
$sql = DB('*','users','idcompany='.$idc . ' ORDER BY is_fired, id');
foreach ($sql as &$user) {
    $user['online'] = false;
    if (in_array($user['id'], $onlineUsers) || $user['activity'] > time() - 180) {
        $user['online'] = true;
    }
}
$isFiredShown = false;
