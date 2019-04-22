<?php
$url = $_SERVER['REQUEST_URI'];
$url = str_replace('/', '', $url);
$url = str_replace('tasks', '', $url);
if(empty($url)) {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status!="done"';
}
if($url == 'inbox') {
	$otbor = 'worker='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'outbox') {
	$otbor = 'manager='.$GLOBALS["id"].' and status!="done"';
}
if($url == 'new' or $url == 'returned' or $url == 'inwork') {
	$otbor = '(status = "new" or status = "inwork" or status = "returned" or status = "overdue") and (worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].')';
}
if($url == 'overdue' or $url == 'pending' or $url == 'done' or $url == 'postpone' ) {
	$otbor = '(worker='.$GLOBALS["id"].' or manager = '.$GLOBALS["id"].') and status="'.$url.'"';
}
if ($url == 'canceled') {
	$otbor = 'status="canceled" and (worker=' . $GLOBALS["id"] . ' or manager = ' . $GLOBALS["id"] . ')';
}
$tasks = DB('*','tasks',$otbor . ' order by datedone');


// функция вывода карточки задачи
function Task($id,$name,$status,$data,$view,$manager,$langc,$color) {
	
	// общее
	
	$border = 'border-'.$color;
	$icon = '';
	$date = '';
	// высчитываем дату и название месяца
	
	if ($langc == 'ru') {
		$arr = ['января',  'февраля',  'марта',  'апреля',  'мая',  'июня',  'июля',  'августа',  'сентября',  'октября',  'ноября',  'декабря'];
		$_tasknew = 'Новая задача';
	}
	if ($langc == 'en') {
		$arr = ['january',  'february',  'march',  'april',  'may',  'june',  'july',  'august',  'september',  'october',  'november',  'december'];
		$_tasknew = 'New task';
	}
	
	$day = date("j", strtotime($data));
	$month = (date("n", strtotime($data)))-1;
	
	// условия и проверка
	
	if ($status == 'new') {}
	if ($status == 'overdue') {$icon = '<i class="fab fa-gripfire text-'.$color.' float-right"></i>';}
	if ($status == 'postpone') {$color = 'warning'; $icon = '<i class="fab fa-gripfire text-danger float-right"></i>';} 
	if ($view == '0') {$badge = '<span class="badge badge-info">'.$_tasknew.' <i class="fas fa-plus"></i></span>';} else {$badge = '';}
	
	// определяем имя и фамилию постановщика
	
	$namem = DBOnce('name','users','id='.$manager);
	$surnamem = DBOnce('surname','users','id='.$manager);
	
	// количество комментариев
	$countcomments = DBOnce('COUNT(*) as count','comments','idtask='.$id);
	if ($countcomments > 0) {
		$comments = '<span class="badge badge-light"><i class="fas fa-comments"></i> '.$countcomments.'</span>';
	} else {
		$comments = '';
	}
	
	echo '<a href="/task/'.$id.'/">
		<div class="card-body position-relative border-bottom">
			<h6 class="card-title mb-3 '.$border.'">'.$name.'</h6>
			<span class="badge badge-light manager">'.$namem.' '.$surnamem.'</span>
			'.$comments;
			if ($status != 'pending') {
				$date =	'<span class="badge badge-'.$color.'">'.$day.' '.$arr[$month].'</span>';
			}
			if ($status == 'postpone') {
				$data_postp = DBOnce('datepostpone','tasks','id='.$id);
				$day_postp = date("j", strtotime($data_postp));
				$month_postp = (date("n", strtotime($data_postp)))-1;
				$date =	'<span class="badge badge-danger">'.$day.' '.$arr[$month].' </span><i class="fas fa-angle-double-right ml-1 mr-1"></i><span class="badge badge-'.$color.'">'.$day_postp.' '.$arr[$month_postp].' </span>';
			}
		echo $date.$badge.$icon.'
		</div>
</a>';
	}	
?>


