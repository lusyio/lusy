<?php 
$lastid = $_POST['param1'];
$lastidnew = DBOnce('id','log','recipient = "'.$id.'" order by datetime desc');
 
if ($lastid != $lastidnew) { 

$log = DB('*','log','id='.$lastidnew);

foreach ($log as $l) {
include 'logfunction.php';?>
<li id="<?=$lastidnew?>" class="newitem">
	<span class="before <?=$iconcolor?> newbefore"><i class="<?=$icon?>"></i></span>
	<div class="position-relative">
		<span class="date"><?=$datetime?></span>
		<img src="/upload/avatar-1.jpg" class="avatar mr-1"/>
		<a href="/profile/<?=$idsender?>/" class="font-weight-bold"><?=$nameuser . ' ' . $surnameuser ?></a>
	</div>
	<p class="mt-2"><?=${'l_'.$l['action']}?><?=$taskpart?></p>
	<?=$comment?>
</li>
<?php }
} else {
	echo $lastidnew;
}
