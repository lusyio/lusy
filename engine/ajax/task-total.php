<div class="anim-show">
<div class="row">
	<div class="col-sm-3">
		<a href="/tasks/">
			<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-tasks text-primary mr-1"></i></span> 
			<p><span class="h3"><?=DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status!="done"');?></span><br><span class="small text-muted">Всего задач</span></p> 
		</a>
	</div>
	<div class="col-sm-3">
		<a href="/tasks/new/">
			<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-bolt text-warning mr-1"></i></span> 
			<p><span class="h3"><?=DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="new"');?></span><br><span class="small text-muted">В работе</span></p> 
		</a>
	</div>
	<div class="col-sm-3">
		<a href="/tasks/pending/">
			<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-search text-success mr-1"></i></span> 
			<p><span class="h3"><?=DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="pending"');?></span><br><span class="small text-muted"><?=$_inreview?></span></p> 
		</a>
	</div>
	<div class="col-sm-3">
		<a href="/tasks/overdue/">
			<span class="h3 font-weight-bold float-left mr-1"><i class="fas fa-sad-tear text-danger mr-1"></i></span> 
			<p><span class="h3"><?=DBOnce('COUNT(*) as count','tasks','worker='.$id.' and status="overdue"');?></span><br><span class="small text-muted">Просроченно</span></p> 
		</a>
	</div>
</div>
</div>
