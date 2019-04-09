<?php
global $id;
if ($id != $worker and $id != $manager) {
	echo "<script>document.location.href = '/tasks/'</script>";
} else {

if ($id == $worker) {
	$role = 'worker';
}
if ($id == $manager) {
	$role = 'manager';
}
if ($id == $worker and $id == $manager) {
	$role = 'manager';
}


if ($status == 'overdue') {
	$status = 'new';
}



// раскидываем по статусам
include 'control/'.$role.'/'.$status.'.php';
	
}
?>

