<?php
$id = $GLOBALS["id"];
$idc = $GLOBALS["idc"];

if (empty($_GET['profile'])) {
	echo "<script>document.location.href = '/profile/".$id."/'</script>";
} else {

$fio = DBOnce('name','users','id='.$_GET["profile"]) . ' ' . DBOnce('surname','users','id='.$_GET["profile"]);	

$phone = DBonce('phone','users','id='.$_GET["profile"]);
if (empty($phone)) {
	$phone = '--';
} else {
	$phone = strval($phone);
	$phone = '+'.substr($phone, 0, 1).' ('.substr($phone, 1, 3).') '.substr($phone, 4, 3).'-'.substr($phone, 7, 2).'-'.substr($phone, 9, 2);
}

$email = DBonce('email','users','id='.$_GET["profile"]);
if (empty($email)) {
	$email = '--';
}

if ($id == $_GET['profile']) {
	 // если мой профиль
	 
}

if ($id != $_GET['profile']) {
	$idcuser = DBOnce('idcompany','users','id='.$_GET['profile']);
	if ($idcuser == $idc) {
		// если чужой
		
	} else {
		echo "<script>document.location.href = '/'</script>";
	}
}
}