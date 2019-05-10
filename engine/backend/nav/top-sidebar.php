<?php
require_once 'engine/backend/functions/common-functions.php';

$id = $GLOBALS["id"];
$newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');
$cometHash = authorizeComet($id);
function avatartop(){
	$id = $GLOBALS["id"];
	$filename = 'upload/avatar/'.$id.'.jpg';
	if (file_exists($filename)) {
		$avatar = '<img src="/'.$filename.'" class="avatar mr-1"/>';
		echo $avatar;
	} else {
		$avatar = '<img src="/upload/avatar/0.jpg" class="avatar mr-1"/>';
		echo $avatar;
	}
}
function fiotop(){
	$name = $GLOBALS["nameu"];
	$surname = $GLOBALS["surnameu"];
	echo $name . ' ' . $surname;
}
$namec = $GLOBALS["namec"];