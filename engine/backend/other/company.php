<?php
global $idc;
$namecompany = DBOnce('idcompany','company','id='.$idc);
$sql = DB('*','users','idcompany='.$idc.' and role="worker" order by points desc');
function userpic($id) {
		$level = floor(DBOnce('points','users','id='.$id)/1000);
		echo '<div class="user-pic position-relative" style="width:85px"><span class="rounded-circle bg-primary level">'.$level.'</span>
		<a href="/profile/'.$id.'/">'.avatar($id).'</a></div>';
	}
function avatar($id) {
		$filename = 'upload/avatar/'.$id.'.jpg';
		if (file_exists($filename)) {
			$avatar = '<img src="/'.$filename.'" class="avatar-img rounded-circle"/>';
			return $avatar;
		} else {
			$avatar = '<img src="/upload/avatar/0.jpg" class="avatar-img rounded-circle w-100 mb-4"/>';
			return $avatar;
		}
}