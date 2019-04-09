<?php
function fio(){
	$name = $GLOBALS["nameu"];
	$surname = $GLOBALS["surnameu"];
	echo $name . ' ' . $surname;
}
function role(){
	$role = $GLOBALS["roleu"];
	echo $GLOBALS["_$role"];
}
$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status!="done"';
$tasks = DB('*','tasks',$otbor);