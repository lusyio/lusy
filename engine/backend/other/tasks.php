<?php
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/', '', $url);
$url = str_replace('tasks', '', $url);
if(empty($url)) {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status!="done"';
}
if($url == 'inbox') {
	$otbor = 'worker='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'outbox') {
	$otbor = 'manager='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'new' or $url == 'overdue' or $url == 'pending' or $url == 'done' or $url == 'postpone') {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status="'.$url.'"';
}

$tasks = DB('*','tasks',$otbor);
?>
