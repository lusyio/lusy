<?php

global $id;
global $idc;
global $pdo;
global $roleu;
global $tariff;


if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/settings-functions.php';

$tryPremiumLimits = getFreePremiumLimits($idc);

$users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
