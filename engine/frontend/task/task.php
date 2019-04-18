<div class="container-fluid" id="task">
		<div class="row justify-content-center">
          <div class="col-12 col-lg-10">
			<div class="card mb-5">
				<div class="card-body">
					<h2 class="<?=$border?>"><?=$nametask?></h2>
                    <h6 class="text-ligther"><i class="far fa-calendar-times custom"></i>  <?=$datedone?> <?=$GLOBALS["_$status"]?> </h6>
<!--			<p class="font-weight-bold text-uppercase mt-4"><span class="text-ligther"><?=$GLOBALS["_createdby"]?></span> <a href="/profile/<?=$manager?>/"><?=$managername?> <?=$managersurname?></a><span class="float-right text-ligther"><i class="far fa-calendar-alt mr-2"></i><?=$datecreate?></span></p> -->
					<div class="mt-5 mb-5 text-justify"><?=$description?></div>
					
					<div id="control">
						<?php 
							include 'engine/backend/task/task/control/'.$role.'/'.$status.'.php';
							include 'engine/frontend/task/control/'.$role.'/'.$status.'.php';
						?>
					</div>
				</div>
				<div class="card-footer pb-3">
					<div class="col">
					<div class="row">
						<div class="col-">
<!--							<p class="font-weight-bold text-ligther text-uppercase">Дедлайн</p>-->
<!--							<p class="mb-0"><i class="far fa-calendar-alt mr-2 --><?//=$color?><!--"></i> --><?//=$datedone?><!--</p>-->
                            <p class="font-weight-bold text-ligther text-uppercase">Статус</p>
                            <span class="status-icon rounded-circle noty-m <?=$bg1?>"><i class="<?=$ic1?> custom"></i></span>
                            <span class="status-icon rounded-circle noty-m <?=$bg2?>"><i class="<?=$ic2?> custom"></i></span>
                            <span class="status-icon-last rounded-circle noty-m <?=$bg3?>"><i class="<?=$ic3?> custom"></i></span>
                        </div>

<!--
<div class="col- ml-4">-->
<!--							<p class="font-weight-bold text-ligther text-uppercase">Статус</p>-->
<!--							<p class="mb-0">--><?//=$icon?><!-- --><?//=$GLOBALS["_$status"]?><!--</p>-->
<!--						</div>-->
						<div class="col- ml-5">
							<p class="font-weight-bold text-ligther text-uppercase">Постановщик</p>
							<p class="mb-0"><img src="/upload/avatar/<?=$manager?>.jpg" class="avatar mr-1"> <?=$managername?> <?=$managersurname?></p>
						</div>
						<div class="col- ml-5">
							<p class="font-weight-bold text-ligther text-uppercase">Исполнитель</p>
							<p class="mb-0"><img src="/upload/avatar/<?=$worker?>.jpg" class="avatar mr-1"> <?=$workername?> <?=$workersurname?></p>
						</div>
					</div>
				</div> 
				</div>
			</div>
				
				<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Комментарии (1)</a>
    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Файлы (0)</a>
    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Информация</a>
    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-log" role="tab" aria-controls="nav-log" aria-selected="false">Журнал</a>
  </div>
</nav>
<div class="tab-content bg-white p-3" id="nav-tabContent">
  	<div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
	  	<div class="row">
			<div class="col-sm-12 comin">
				<input class="form-control" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
				<button type="submit" id="comment" class="btn btn-primary" title="<?=$_send?>"><i class="fas fa-paper-plane"></i></button>
			</div>
		</div>
		<div id="comments"></div>
  </div>
  <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
	  файлы
  </div>
  <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
	  информация
  </div>
  <div class="tab-pane fade" id="nav-log" role="tabpanel" aria-labelledby="nav-log-tab">
	  журнал
  </div>
</div>
				
		</div>	
	</div>
</div>
</div>
<script>
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>
<script src="/assets/js/task.js"></script>
