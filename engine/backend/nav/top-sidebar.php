<?php
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

global $_searchtext;
global $_logout;
global $_profile;
global $_history;
global $_settings;
global $id;
global $idc;
global $roleu;
global $namec;
global $_free;
global $_premium;
global $tariff;
global $title;

if ($tariff == 1) {
    $tariffName= $_premium;
} else {
    $tariffName = $_free;
}
$GLOBALS['cometHash'] = authorizeComet($id);
if ($idc == 1) {
    $GLOBALS['supportCometHash'] = authorizeComet(1);
}
if ($roleu == 'ceo') {
    $isCeo = true;
} else {
    $isCeo = false;
}
