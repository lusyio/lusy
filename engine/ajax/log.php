<?php
$log = DB('*','log','recipient = "'.$id.'" order by datetime desc limit 30');
foreach ($log as $l) {
include('logfunction.php');?>
<li>
	<span class="before <?=$iconcolor?>"><i class="<?=$icon?>"></i></span>
	<div class="position-relative">
		<span class="date"><?=$datetime?></span>
		<img src="/upload/avatar/<?=$idsender?>.jpg" class="avatar mr-1"/>
		<a href="/profile/<?=$idsender?>/" class="font-weight-bold"><?=$nameuser . ' ' . $surnameuser ?></a>
	</div>
	<p class="mt-2"><?=${'l_'.$l['action']}?><?=$taskpart?></p>
	<?=$comment?>
</li>    
<?php } ?>