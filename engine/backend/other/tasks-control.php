<?php
	$url = $_SERVER['REQUEST_URI'];
	$url = str_replace('/', '', $url);
	$url = str_replace('tasks', '', $url);
	echo $url;
	
?>
