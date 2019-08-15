<?php
$d = date("d"); // текущий день
$dlog = date("d", strtotime($l['datetime'])); // день из лога
if ($d == $dlog) { // сравниваем их, если равны, то писать только время
	$datetime = date("H:i", strtotime($l['datetime']));	
} else {
	$datetime = date("d.m H:i", strtotime($l['datetime']));
}
$idlog = $l['id'];
$idsender = $l['sender'];
// получаем информацию о юзере
$sql = 'SELECT name, surname, login FROM users where id = "'.$idsender.'" limit 1';
$row = $pdo->query($sql);
$result = $row->fetch();
$nameuser = $result[0];
$surnameuser = $result[1];
if (empty($nameuser)) {
	$nameuser = $result[2];
	$surnameuser = null;
}

if (!empty($l['task'])) {

	// получаем информацию о задаче
	$nametask = DBOnce('name','tasks','id='.$l['task']);
	$taskpart = ' <a href="/task/'.$l['task'].'/">'.$nametask.'</a>';
	
} else {
	$taskpart = '';
}
if (!empty($l['comment'])) {

	// получаем информацию о задаче
	$comment = DBOnce('comment','comments','id='.$l['comment']);
	$comment = '<div class="comment p-2 pl-3"><p class="mb-0">'.$comment.'</p></div>';
} else {
	$comment = '';
}
// иконки и стили
if($l['action'] == 'reg') { $icon = 'fas fa-user'; $iconcolor = 'bg-dark';}
if($l['action'] == 'createtask') { $icon = 'fas fa-plus'; $iconcolor = 'bg-primary';}
if($l['action'] == 'comment') { $icon = 'fas fa-comment'; $iconcolor = 'bg-secondary';}
if($l['action'] == 'overduetask') { $icon = 'fas fa-exclamation'; $iconcolor = 'bg-danger';}
?>
