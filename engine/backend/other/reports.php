<?php

global $id;
global $idc;
global $pdo;
global $roleu;
global $tariff;


if ($roleu != 'ceo') {
    header('location:/');
    ob_flush();
    die;
}

require_once __ROOT__ . '/engine/backend/functions/common-functions.php';
require_once __ROOT__ . '/engine/backend/functions/settings-functions.php';

$tryPremiumLimits = getFreePremiumLimits($idc);

$users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
