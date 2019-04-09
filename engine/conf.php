<?php	
	$errors = 1;
	if ($errors == 1) {
	  	ini_set('error_reporting', E_ALL);
	 	ini_set('display_errors', 1);
	  	ini_set('display_startup_errors', 1);
  	}
	$user = 'root';
	$pass = '123456';
	$pdo = new PDO('mysql:host=localhost;dbname=mybd', $user, $pass);
	
	// текущая дата
	$now = date("Y-m-d");
	$datetime = date("Y-m-d H:i:s");
	
	// массив внешних страниц
	$pages = ['login','reg','logout'];
	
	// формирование массива из базы данных для дальнейшего вывода в цикле
	function DB($zapros,$db,$where) {
		global $pdo;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where;
		$sql = $pdo->prepare($sql);
		$sql->execute();
		$sql = $sql->fetchAll(PDO::FETCH_BOTH);
		return $sql;
	}
	
	// функция однократного запроса
	function DBOnce($zapros,$db,$where) {
		global $pdo;
		if ($where != null) {
			$where = ' WHERE '.$where;
		}
		$sql = 'SELECT '.$zapros.' FROM '.$db.$where.' limit 1';
		$row = $pdo->query($sql);
		// Перебор и вывод результатов
		$result = $row->fetch();
		return $result[0];
	}
	
	// функция подключения backend и frontend
	function inc($module,$component) {
		$backend = 'engine/backend/'.$module.'/'.$component.'.php';
		$frontend = 'engine/frontend/'.$module.'/'.$component.'.php';
		
		if (file_exists($backend)) {
			include $backend;
		}
		
		if (file_exists($frontend)) {
			include $frontend;
		}
	}
	
	// функция добавления записи в лог
	function newLog($action,$idtask,$comment,$sender,$recipient) {
		global $pdo;
		global $id;
		global $idc;
		global $datetime;
		
		if (empty($idtask)) {$idtask = 0;}
		if (empty($comment)) {$comment = 0;}
		
		$intolog = $pdo->prepare("INSERT INTO log SET action = :action, task = :idtask, comment = :comment, sender = :sender, recipient = :recipient, idcompany = :idcompany, datetime = :datetime");
		$intolog->execute(array('action' => $action, 'idtask' => $idtask, 'comment' => $comment, 'sender' => $sender, 'recipient' => $recipient, 'idcompany' => $idc, 'datetime' => $datetime));
	}
	
	// функция создания задач
	function createTask($name,$description,$worker,$datedone) {
		global $pdo;
		global $now;
		global $id;
		global $idc;
		
		$sql = $pdo->prepare("INSERT INTO tasks SET name = :name, description = :description, datecreate = '".$now."', datedone = :datedone,status = 'new', manager = '".$id."', worker = :worker, idcompany = '".$idc."'");
		$sql->execute(array('name' => $name, 'description' => $description, 'worker' => $worker, 'datedone' => $datedone));
		$idtask = $pdo->lastInsertId();
		
		if (!empty($idtask)) {
			newLog('createtask',$idtask,'',$id,$worker);
		}
	}
	
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
		
	function userpic($id) {
		$level = floor(DBOnce('points','users','id='.$id)/1000);
		echo '<div class="user-pic position-relative" style="width:85px"><span class="rounded-circle bg-primary level">'.$level.'</span>
		<a href="/profile/'.$id.'/">'.avatar($id).'</a></div>';
	}
	