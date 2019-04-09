<?php
$menu[] = ['Главная' => ['/',$_main,'fas fa-home']];
$menu[] = ['tasks' => ['/tasks/',$_tasks,'fas fa-tasks']];
$menu[] = ['newtask' => ['/task/new/',$_tasknew,'fas fa-plus']];
$menu[] = ['Лог' => ['/log/',$_notifications,'fas fa-list-alt']];
$menu[] = ['awards' => ['/awards/',$_awards,'fas fa-trophy']];
$menu[] = ['Компания' => ['/company/',$_company,'fas fa-users']];
// Если пользователя глава компании, то включить ему лог и отчеты
if ($roleu == 'seo') {
	$menu[] = ['Лог' => ['/log/',$_log,'fas fa-users']];
	$menu[] = ['Отчет' => ['/report/',$_report,'fas fa-clipboard']];
	$menu[] = ['plugins' => ['','','']];
}