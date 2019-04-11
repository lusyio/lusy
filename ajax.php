<?php
if(isset($_POST['ajax']) && !empty($_POST['ajax'])) {
	
	// подключаем pdo
	require_once(realpath('conf.php'));
	
	// вычисляем user id
	$id = $_POST['usp'] - 345;
	
	// вычисляем id компании пользователя
	$idc = DBOnce('idcompany','users','id='.$id);
	
	// определяем язык компании
	$langc = DBOnce('lang','company','id='.$idc);
	
	// подключаем языковой файл
	require_once(realpath('engine/backend/lang/'.$langc.'.php'));
	
	// кладем запрос в переменную
	$zapros = $_POST['ajax'];
	
	// подключаем файл php
	require_once(realpath('engine/ajax/'.$zapros.'.php'));
} else {
	header("location:/");
}