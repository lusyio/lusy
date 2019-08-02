<div class="user-pic text-center position-relative">
	<div class="position-relative user-pic-other">
		<span class="rounded-circle bg-primary level"><?=$level?></span>
		<a href="/profile/<?=$id?>/"><?=avatars()?></a>
	</div>
	<div class="font-weight-bold mt-2 mb-2"><a href="/profile/<?=$id?>/"><?=username()?></a></div>
	<div class="progress">
  <div class="progress-bar" role="progressbar" style="width: <?=$pros?>%;" aria-valuenow="<?=$pros?>" aria-valuemin="0" aria-valuemax="100"><span class="d-flex m-auto"><span class="mr-1"><?=$points?></span><span>/</span><span class="ml-1"><?=($level+1)*1000?></span></span></div>
</div>
	
	<a href="/log/"><div class="d-flex mt-2 mb-5 indy">
		<div><span class="rounded-circle bg-primary noty"><i class="fas fa-plus"></i></span><span class="noty2"><?=$newtask?></span></div>
		<div><span class="rounded-circle bg-secondary noty"><i class="fas fa-comment"></i></span><span class="noty2"><?=$comments?></span></div>
		<div><span class="rounded-circle bg-danger noty"><i class="fas fa-exclamation"></i></span><span class="noty2"><?=$overduetask?></span></div>
		<div><span class="rounded-circle bg-success noty"><i class="fas fa-check"></i></span><span class="noty2"><?=$completetask?></span></div>
	</div></a>
</div>