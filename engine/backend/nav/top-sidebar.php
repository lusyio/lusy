<?php
require_once 'engine/backend/functions/common-functions.php';

global $_searchtext;
global $_logout;
global $_profile;
global $_history;
global $_settings;
global $id;
global $namec;

$GLOBALS['cometHash'] = authorizeComet($id);
