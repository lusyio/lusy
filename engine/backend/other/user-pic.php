<?php
global $id;
function avatars() {
	global $id;
	$filename = 'upload/avatar/'.$id.'.jpg';
	if (file_exists($filename)) {
		$avatar = '<img src="/'.$filename.'" class="avatar-img rounded-circle"/>';
		echo $avatar;
	} else {
		$avatar = '<img src="/upload/avatar/0.jpg" class="avatar-img rounded-circle w-100 mb-4"/>';
		echo $avatar;
	}
}
function username(){
	$name = $GLOBALS["nameu"];
	$surname = $GLOBALS["surnameu"];
	echo $name . ' ' . $surname;
}
$newtask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "new" and worker='.$id);
$overduetask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "overdue" and worker='.$id);
$completetask = DBOnce('COUNT(*) as count','tasks','view="0" and status = "done" and worker='.$id);
$usertasks = DB('id','tasks','worker='.$id.' or manager='.$id);
$idtasks = [];
foreach ($usertasks as $n) {
	$idtasks[] = $n["id"];
}
$ids = join('","',$idtasks); 
$comments = DBOnce('COUNT(*) as count','comments','view="0" and idtask IN ("'.$ids.'") and iduser !='.$id);
$level = floor(DBOnce('points','users','id='.$id)/1000);

$points = DBOnce('points','users','id='.$id);

$pros = round((100 * ($points-$level*1000)) / 1000, 0);
