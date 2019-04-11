<?php
	if(empty($id)) {
		$id = $GLOBALS["id"];
	}
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status!="done"';
	$inbox = DBOnce('COUNT(*) as count','tasks','worker='.$GLOBALS["id"].' and status!="done"');
	$outbox = DBOnce('COUNT(*) as count','tasks','manager='.$GLOBALS["id"].' and status!="done"');
	
	echo '<div id="nav-tasks"><li class="nav-item"><a class="nav-link " href="/tasks/"><i class="fas fa-tasks mr-2"></i> '.$GLOBALS["_tasks"].' <span class="badge badge-light float-right"><i class="fas fa-angle-down"></i></span></a>';
	echo '<ul class="navbar-nav pt-2" style="padding-left:28px;">';
	
	if ($inbox > 0) {
		echo '<li class="nav-item pb-2"><a href="/tasks/inbox/" class="nav-link">' . $GLOBALS["_inbox"] . ' <span class="badge badge-dark float-right"><i class="fas fa-file-import"></i> ' .$inbox. '</span></a></li>';
	}
	
	if ($outbox > 0) {
		echo '<li class="nav-item pb-2"><a href="/tasks/outbox/" class="nav-link">' . $GLOBALS["_outbox"] . ' <span class="badge badge-dark float-right"><i class="fas fa-file-export"></i> ' . $outbox . '</span></a></li>';
	}
	
	
	$sql = DB('COUNT(*) as count, status','tasks',$otbor.' group by status');
	
	foreach ($sql as $n) {
		$status = $n['status'];
		$color = '';
		if ($status == 'new') { $color = 'primary';}
		if ($status == 'pending') { $color = 'success';}
		if ($status == 'overdue') { $color = 'danger';}
		if ($status == 'postpone') { $color = 'warning';}
		echo '<li class="nav-item pb-2"><a href="/tasks/'.$status.'/" class="nav-link">' . $GLOBALS["_$status"] . ' <span class="badge badge-'.$color.' float-right">' . $n['count'] . '</span></a></li>';
	}
	echo '</ul></li></div>';