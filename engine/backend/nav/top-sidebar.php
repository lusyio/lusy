<?php
require_once 'engine/backend/functions/common-functions.php';

global $_searchtext;
global $_logout;
global $_profile;
global $_history;
global $_settings;
global $id;
global $namec;

$newOverdueCount = DBOnce('count(*)','tasks','worker='.$id.' or manager='.$id);
$newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');
$newLogCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action NOT LIKE "comment"');
$newCommentCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action LIKE "comment"');
$GLOBALS['cometHash'] = authorizeComet($id);
