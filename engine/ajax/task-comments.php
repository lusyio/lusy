<?php
$idtask = $_POST['it'];	
$countcomments = DBOnce('COUNT(*) as count','comments','idtask='.$idtask);
				
if ($countcomments > 0) {
echo '<hr><p class="font-weight-bold text-uppercase mt-4"><span class="text-ligther">'.$_comments.' ('.$countcomments.')</span></p>';
					
$comments = $pdo->prepare('SELECT id, iduser, comment, datetime FROM `comments` where idtask = "'.$idtask.'" ORDER BY datetime desc');
$comments->execute();
$comments = $comments->fetchAll(PDO::FETCH_BOTH);

foreach ($comments as $c) { 
	
// получаем информацию о юзере
$sql = 'SELECT name, surname, login FROM users where id = "'.$c['iduser'].'" limit 1';
$row = $pdo->query($sql);
$result = $row->fetch();
$nameuser = $result[0];
$surnameuser = $result[1];
$d = date("d"); // текущий день
$dc = date("d", strtotime($c['datetime'])); // день из лога
if ($d == $dc and $id == $c['iduser']) {
	$delete = '<button type="button" value="#'.$c['id'].'" class="btn btn-link text-danger delc"><i class="fas fa-times"></i></button>';
} else {
	$delete = '';
}
if ($d == $dc) { // сравниваем их, если равны, то писать только время
	$dc = date("H:i", strtotime($c['datetime']));
} else {
	$dc = date("d.m H:i", strtotime($c['datetime']));
}

echo '<div class="mb-3 comment" id="'.$c['id'].'"><div class="position-relative">
		<span class="date">'.$dc.'</span>
		<img src="/upload/avatar/' . $c['iduser'] . '.jpg" class="avatar mr-3">
		<a href="/profile/' . $c['iduser'] . '/" class="font-weight-bold">' . $nameuser . ' ' . $surnameuser . '</a>
		' . $delete . '
	</div>
		<p class="mt-1 mb-4">' . $c['comment'] . '</p></div>';
} 
} else {
	echo '<hr><p class="text-center text-ligther mt-5">'.$_nocomment.'</p>';
}
?>
<script>
$(document).ready(function() {
	$(".delc").click(function(){
		$idcom = $( this ).val();
		$.post( "/ajax.php", { usp: $usp, ic: $idcom, ajax: 'task-comments-del' }) .done(function() { $($idcom).fadeOut(); setTimeout(function () { $($idcom).remove(); }, 500);  });
	});
});
</script>