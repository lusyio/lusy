<?php
if ($companyUsageSpacePercent > 90){
    $bguser = 'bg-danger';
    $bgall = 'bg-danger';

} else {
    $bguser = 'bg-dark';
    $bgall = 'bg-primary';
}
?>
<nav class="navbar-expand-lg flex-column" style="width: 85%">

  <div class="collapse navbar-collapse" id="navbarNav">
  		<ul class="navbar-nav w-100">
			<li class="nav-item pb-2"><a class="nav-link" href="/"><i class="fas fa-home mr-2"></i> <?=$_main?></a></li>
			<li class="nav-item pb-2">
				<a class="nav-link" href="/tasks/">
					<i class="fas fa-tasks mr-2"></i> <?=$_tasks?>
					<div class="float-right">
						<span class="badge badge-primary float-left" style=" border-top-right-radius: 0px; border-bottom-right-radius: 0px; "><?=$manager?></span>
						<span class="badge badge-dark float-right" style=" border-top-left-radius: 0px; border-bottom-left-radius: 0px; "><?=$worker?></span>
					</div>
				</a>
			</li>
			<li class="nav-item"><a class="nav-link" href="/task/new/"><i class="fas fa-plus mr-2"></i> <?=$_tasknew?></a></li>
			<hr class="w-100">
			<li class="nav-item pb-2"><a class="nav-link" href="/awards/"><i class="fas fa-trophy mr-2"></i> <?=$_awards?></a></li>
			<li class="nav-item pb-2"><a class="nav-link" href="/company/"><i class="fas fa-users mr-2"></i> <?=$_company?><span class="badge badge-secondary float-right">#4</span></a></li>
			<li class="nav-item pb-2 files-nav">
				<a class="nav-link" href="/storage/">
					<i class="fas fa-hdd mr-2"></i>
					<?=$_storage?>
					<div class="progress mt-2">
                        <div class="progress-bar <?=$bgall?>" role="progressbar" style="width: <?= $companyUsageSpacePercent ?>%" aria-valuenow="<?= $companyUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titlecompanyusage"]?>"></div>
                        <div class="progress-bar <?=$bguser?> role="progressbar" style="width: <?= $userUsageSpacePercent ?>%" aria-valuenow="<?= $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titleuserusage"]?>"></div>
					</div>
				</a>
			</li> 
		</ul>
  </div>
</nav>