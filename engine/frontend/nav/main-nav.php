<?php
if ($companyUsageSpacePercent > 90){
    $bguser = 'bg-danger';
    $bgall = 'bg-danger';

} else {
    $bguser = 'bg-dark';
    $bgall = 'bg-primary';
}
?>
<nav class="navbar-expand-lg flex-column">

  <div class="collapse navbar-collapse navbarNav" id="navbarNav">
  		<ul class="navbar-nav w-100">
            <?php if (in_array('main', $menu[$roleu])): ?>
			<li class="nav-item pb-2"><a class="nav-link" href="/"><i class="fas fa-home mr-2 fa-fw"></i> <?=$_main?></a></li>
            <?php endif; ?>
            <?php if (in_array('tasks', $menu[$roleu])): ?>
            <li class="nav-item pb-2">
				<a class="nav-link" href="/tasks/">
					<i class="fas fa-tasks mr-2 fa-fw"></i> <?=$_tasks?>
					<div class="float-right">
						<span class="badge badge-primary float-left" style=" border-top-right-radius: 0px; border-bottom-right-radius: 0px; " title="<?=$GLOBALS['_outbox']?>"><?=$manager?></span>
						<span class="badge badge-dark float-right" style=" border-top-left-radius: 0px; border-bottom-left-radius: 0px; " title="<?=$GLOBALS['_inbox']?>"><?=$worker?></span>
					</div>
				</a>
			</li>
            <?php endif; ?>
            <?php if (in_array('newTask', $menu[$roleu])): ?>
			<li class="nav-item"><a class="nav-link" href="/task/new/"><i class="fas fa-plus mr-2 fa-fw"></i> <?=$_tasknew?></a></li>
			<hr class="w-100">
            <?php endif; ?>
            <?php if (in_array('company', $menu[$roleu])): ?>
			<li class="nav-item pb-2"><a class="nav-link" href="/company/"><i class="fas fa-users mr-2 fa-fw"></i> <?=$_company?></a></li>
            <?php endif; ?>
            <?php if (in_array('reports', $menu[$roleu])): ?>
                <li class="nav-item pb-2"><a class="nav-link" href="/reports/"><i class="fas fa-chart-pie mr-2 fa-fw"></i> <?=$GLOBALS['_reports']?></a></li>
            <?php endif; ?>
            <?php if (in_array('storage', $menu[$roleu])): ?>
			<li class="nav-item pb-2 files-nav">
				<a class="nav-link" href="/storage/">
					<i class="fas fa-hdd mr-2 fa-fw"></i>
					<?=$_storage?>
					<div class="progress mt-2">
                        <div class="progress-bar <?=$bguser?>" role="progressbar" style="width: <?= $userUsageSpacePercent ?>%" aria-valuenow="<?= $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titleuserusage"]?>"></div>
                        <div class="progress-bar <?=$bgall?>" role="progressbar" style="width: <?= $companyUsageSpacePercent - $userUsageSpacePercent ?>%" aria-valuenow="<?= $companyUsageSpacePercent - $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100" title="<?=$GLOBALS["_titlecompanyusage"]?>"></div>
					</div>
				</a>
			</li>
            <?php endif; ?>

        </ul>
  </div>
</nav>