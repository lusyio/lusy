<div class="row">
	<div class="col-sm-5">
		<div class="card position-relative" style=" height: 161px; padding: 56px 40px; /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#febbbb+0,fe9090+45,ff5c5c+100;Red+3D+%231 */ background: #febbbb; /* Old browsers */ background: -moz-linear-gradient(top, #febbbb 0%, #fe9090 45%, #ff5c5c 100%); /* FF3.6-15 */ background: -webkit-linear-gradient(top, #febbbb 0%,#fe9090 45%,#ff5c5c 100%); /* Chrome10-25,Safari5.1-6 */ background: linear-gradient(to bottom, #ffd1a3 0%,#ffad99 45%,#ff7c7c 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */ filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#febbbb', endColorstr='#ff5c5c',GradientType=0 ); /* IE6-9 */ ">
			<span>7</span>
			<span> просроченных задач</span>
			
		</div>
	</div>

	<div class="col-sm-7">
<div class="card bg-dark text-white">
			<div class="card-body position-relative">
				<div class="position-absolute" style=" right: 10px; font-size: 12px; top: 8px; color: #9e9e9e; ">В этом месяце <i class="fas fa-sort-down"></i></div>
				<div class="row">
					<div class="col-sm-3">
						<div class="p-3 rounded" style="background: #2d3035;">
							<h3 class="font-weight-bold text-warning">782</h3>
							<small>Баллов заработано</small>
						</div>
					</div>
					<div class="col-sm-9">
						<div class="row">
							<div class="col-sm-4">
								<h3 class="mt-3" style=" margin-left: 10px; ">23</h3>
								<div class="d-flex">
									<small style=" margin-left: -10px; width: 20px;"><i class="fas fa-check text-success mr-2"></i></small>
									<small>Выполнено<br>задач</small>
								</div>
							</div>
							<div class="col-sm-4">
								<h3 class="mt-3" style=" margin-left: 10px; ">3</h3>
								<div class="d-flex">
									<small style=" margin-left: -10px; width: 20px;"><i class="fas fa-exclamation text-danger mr-2"></i></small>
									<small>Получено просрочек</small>
								</div>
							</div>
							<div class="col-sm-4">
								<h3 class="mt-3" style=" margin-left: 10px; ">32</h3>
								<div class="d-flex">
									<small style=" margin-left: -10px; width: 20px;"><i class="fas fa-comment text-secondary mr-2"></i></small>
									<small>Оставлено комментариев</small>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<div class="card mt-3" style=" border-bottom-left-radius: 0px; border-bottom-right-radius: 0px; ">
	<div class="card-body">
		В работе
	</div>
</div>
<table class="table table-hover" style="box-shadow: 0 0.35rem 1.5rem rgba(18,38,63,.13);background: white">
  <thead style=" font-size: 10px; background: #e6ecef;">
    <tr>
      <th><?=$GLOBALS["_taskname"]?></th>
      <th><?=$GLOBALS["_taskworker"]?></th>
      <th><?=$GLOBALS["_taskmanager"]?></th>
      <th><?=$GLOBALS["_deadline"]?></th>
      <th><?=$GLOBALS["_status"]?></th>
    </tr>
  </thead>
  <tbody>
	  <?php 
	$i = 0;
	foreach ($tasks as $n) {
	$idtask = $n["id"];
	$idworker = $n["worker"];
	$idmanager = $n["manager"];
	$status = $n["status"];
	$name = $n["name"];
	$namew = DBOnce('name','users','id='.$idworker);
	$surnamew = DBOnce('surname','users','id='.$idworker);
	$namem = DBOnce('name','users','id='.$idmanager);
	$surnamem = DBOnce('surname','users','id='.$idmanager);
	$datedone = $n["datedone"];
	$i++;
 ?>	
	<tr id="z_<?=$i?>">
		<td>
			<a href="/task/<?=$idtask?>/"><span class="card-title mb-3 border-primary"><?=$name?></span></a>
		</td>
		<td>
			<a href="/profile/<?=$worker?>"><?=$namew.' '.$surnamew?></a>
		</td>
		<td>
			<a href="/profile/<?=$manager?>"><?=$namem.' '.$surnamem?></a>
		</td>
		<td><?=$datedone?></td>
		<td><?=$GLOBALS["_$status"]?></td>
	</tr>
<?php }?>
  </tbody>
</table>