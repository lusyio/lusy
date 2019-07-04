<?php
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

global $_searchtext;
global $_logout;
global $_profile;
global $_history;
global $_settings;
global $id;
global $idc;
global $namec;
global $_free;
global $_premium;

$tariff = DBOnce('tariff', 'company','id='.$idc);
if ($tariff == 1) {
    $tariff = $_premium;
} else {
    $tariff = $_free;
}
$GLOBALS['cometHash'] = authorizeComet($id);
