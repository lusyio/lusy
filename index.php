<?php 
session_start();
include 'conf.php'; 
include 'engine/backend/other/header.php'; 
include 'engine/frontend/other/header.php';


// проверка на страницы логина и подобные
if (!empty($_GET['folder'])) {
$folder = $_GET['folder'];
if (!empty($folder)) {
	if (in_array($folder, $pages)) {
		inc('other',$folder);
	}
}
}

include 'engine/backend/other/footer.php'; 
include 'engine/frontend/other/footer.php';