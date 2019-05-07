<?php
if(isset($_POST['ajax']) && !empty($_POST['ajax'])) {
	
	// подключаем pdo
	require_once(realpath('conf.php'));

    require_once 'engine/backend/functions/common-functions.php';

    // вычисляем user id
	$id = filter_var($_POST['usp'], FILTER_SANITIZE_NUMBER_INT) - 345;
	
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