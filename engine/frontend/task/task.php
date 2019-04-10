<div class="container-fluid" id="task">
		<div class="row justify-content-center">
          <div class="col-12 col-lg-10">
			<div class="card mb-5">
				<div class="card-body">
			<h2 class="<?=$border?>"><?=$nametask?></h2>
			<p class="font-weight-bold text-uppercase mt-4"><span class="text-ligther"><?=$GLOBALS["_createdby"]?></span> <a href="/profile/<?=$manager?>/"><?=$managername?> <?=$managersurname?></a><span class="float-right text-ligther"><i class="far fa-calendar-alt mr-2"></i><?=$datecreate?></span></p>
			<div class="mt-5 mb-5 text-justify"><?=$description?></div>
			<hr>
				<div class="col">
					<div class="row">
						<div class="col-">
							<p class="font-weight-bold text-ligther text-uppercase">Дедлайн</p>
							<p class="mb-0"><i class="far fa-calendar-alt mr-2 <?=$color?>"></i> <?=$datedone?></p>
						</div>
						<div class="col- ml-4">
							<p class="font-weight-bold text-ligther text-uppercase">Статус</p>
							<p class="mb-0"><?=$icon?> <?=$GLOBALS["_$status"]?></p>
						</div>
						<div class="col- ml-4">
							<p class="font-weight-bold text-ligther text-uppercase">Постановщик</p>
							<p class="mb-0"><img src="/upload/avatar/<?=$manager?>.jpg" class="avatar mr-1"> <?=$managername?> <?=$managersurname?></p>
						</div>
						<div class="col- ml-4">
							<p class="font-weight-bold text-ligther text-uppercase">Исполнитель</p>
							<p class="mb-0"><img src="/upload/avatar/<?=$worker?>.jpg" class="avatar mr-1"> <?=$workername?> <?=$workersurname?></p>
						</div>
					</div>
				</div> 
			
			<div id="control">
						<? // inc('task/task','control');

						if ($id != $worker and $id != $manager) {
							echo "<script>document.location.href = '/tasks/'</script>";
						} else {

						if ($id == $worker) {
							$role = 'worker';
						}
						if ($id == $manager) {
							$role = 'manager';
						}
						if ($id == $worker and $id == $manager) {
							$role = 'manager';
						}

						if ($status == 'overdue') {
							$status = 'new';
						}
						// раскидываем по статусам
						include 'engine/backend/task/task/control/'.$role.'/'.$status.'.php';
							
						 }
						?>
					</div>
						
				<hr>
				<div class="row">
					<div class="col-sm-12 comin">
							<input class="form-control" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
							<button type="submit" id="comment" class="btn btn-primary" title="<?=$_send?>"><i class="fas fa-paper-plane"></i></button>
					</div>
				</div>
				<div id="comments"></div>
			</div>
		</div>	
	</div>
</div>
</div>
<script>
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>
<script src="/assets/js/task.js"></script>
