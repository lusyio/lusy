<style>#workzone {background: transparent !important} </style>
<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-sm-3">
				<a href="/tasks/" class="text-decoration-none">
					<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-tasks text-primary mr-1"></i></span> 
					<p class="mb-0"><span class="h3"><?=$all?></span><br><span class="small text-muted">Всего задач</span></p> 
				</a>
			</div>
			<div class="col-sm-3">
				<a href="/tasks/" class="text-decoration-none">
					<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-bolt text-warning mr-1"></i></span> 
					<p class="mb-0"><span class="h3"><?=$inwork?></span><br><span class="small text-muted"><?=$_inprogress?></span></p> 
				</a>
			</div>
			<div class="col-sm-3">
				<a href="/tasks/" class="text-decoration-none">
					<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-search text-success mr-1"></i></span> 
					<p class="mb-0"><span class="h3"><?=$pending?></span><br><span class="small text-muted"><?=$_pending?></span></p> 
				</a>
			</div>
			<div class="col-sm-3">
				<a href="/tasks/" class="text-decoration-none">
					<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-sad-tear text-danger mr-1"></i></span> 
					<p class="mb-0"><span class="h3"><?=$overdue?></span><br><span class="small text-muted"><?=$_overdue?></span></p> 
				</a>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header font-weight-bold">
		<?=$_history?>
	</div>
	<div class="card-body pb-0 pt-0">
			<div id="log">
				<ul class="timeline" style="bottom: 0px;">
                    <?php foreach ($events as $event): ?>
                        <?php renderEvent($event); ?>
                    <?php endforeach; ?>
					</ul>
			</div>	
	</div>
</div>
