<?php

// Список переменных

// Пользователь

// $loginu - логин пользователя
// $emailu - email пользователя
// $nameu - имя пользователя
// $surnameu - фамилия пользователя

// Компания

// $idc - цифровой id компании
// $namec - название компании
// $premium - оплачен ли премиум доступ

// подключаем бд и получаем id пользователя из куки

// формируем массив $main, в котором содержится основная информация
$id = $_SESSION['id'];
// 1. Подключаемся в таблицу users
$sql = DB('email, name, surname, idcompany, role','users','id='.$id);

foreach ($sql as $main) {
	$emailu = $main['email']; // email пользователя
	$nameu = $main['name']; // имя пользователя
	$surnameu = $main['surname']; // фамилия пользователя
	$idc = $main['idcompany']; // цифровой id компании
	$roleu = $main['role']; // роль пользователя
}
// 2. Подключаемся в таблицу company
$sql = DB('idcompany, premium, lang','company','id='.$idc);

foreach ($sql as $main) {
	$namec = $main['idcompany']; // название компании
	$langc = $main['lang']; // язык компании
	$premium = $main['premium']; // оплачен ли премиум доступ
}
$proverka = ['email'=>$emailu,'name'=>$nameu,'surname'=>$surnameu];


// проверяем, заполнены ли все поля
$new_array = array_filter($proverka, function($element) {
    return empty($element);
});
if (!empty($new_array)) {
foreach(array_keys($new_array) as $key){
	if (!empty($text)) {
    	$text = $text . ', ' . $key;
    } else {
	    $text = $key;
    }
}
// формируем массив отклонений
$alerts = [
		'alert1' => ['danger', 'Необходимо заполнить профиль, указав следующие значения: '.$text.'. <a href="/" class="alert-link">Заполнить</a>'],
	];
}

// подключаем внешние файлы обработчики
include 'engine/backend/lang/'.$langc.'.php';
include 'engine/backend/main/array.php';