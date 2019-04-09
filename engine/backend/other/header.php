<?php
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace('/', '', $url);
	if (!empty($_SESSION['auth'])) {
		include 'engine/backend/main/main.php';
			if (!empty($_GET['task']) or !empty($_GET['tasks'])) {
				$title = 'Задача';
			}
			if (!empty($_GET['profile'])) {
				$title = DBOnce('name','users','id='.$_GET["profile"]) . ' ' . DBOnce('surname','users','id='.$_GET["profile"]);
			}
			if(empty($title)) {
				if(empty($url)) {
				$title = $GLOBALS["_main"];
				} else {
				$title = $GLOBALS["_$url"];
				}
			}
		} else {
		if (in_array($url, $pages)) { 	
			if ($url != 'login') {
				echo "<script>document.location.href = '/login/'</script>";
			}
		} else {
			echo "<script>document.location.href = '/login/'</script>";
		}
	}