<?php
$statusBar = [
    'new' => [
        'border' => 'border-success',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'inwork' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'overdue' => [
        'border' => 'border-danger',
        'bg' => 'badge-danger',
        'bg1' => 'bg-danger',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fab fa-gripfire',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'postpone' => [
        'border' => '',
        'bg' => 'badge-warning',
        'bg1' => 'bg-warning',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'far fa-clock',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'pending' => [
        'border' => 'border-warning',
        'bg' => 'badge-warning',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-warning',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'returned' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'done' => [
        'border' => 'border-success',
        'bg' => 'badge-success',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-success',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'canceled' => [
        'border' => 'border-secondary',
        'bg' => 'badge-danger',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-danger',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-times',
    ],
];

if ($dayost < 0) {
    $statusBar['postpone']['border'] = 'border-danger';
};
if ($view == 0) {
    $statusBar[$status]['border'] = 'border-secondary';
};
if ($id == $worker and $view == 0) {
    $statusBar[$status]['border'] = 'border-primary';
}
?>
<div class="container-fluid" id="task">
    <div class="card">
        <div class="card-body">
            <div class="row" style="margin-top: -21px;">
                <div class="col-4">
					<span class="badge <?=$statusBar[$status]['bg']?>"><?=$GLOBALS["_$status"]?></span>
                </div>
                <div class="col-8">
                    <div class="float-right" title="<?=$GLOBALS["_$status"]?>">

                    	<span class="status-icon rounded-circle noty-m <?=$statusBar[$status]['bg1']?>"><i class="<?=$statusBar[$status]['ic1']?> custom"></i></span>
						<span class="status-icon rounded-circle noty-m <?=$statusBar[$status]['bg2']?>"><i class="<?=$statusBar[$status]['ic2']?> custom"></i></span>
						<span class="status-icon-last rounded-circle noty-m <?=$statusBar[$status]['bg3']?>"><i class="<?=$statusBar[$status]['ic3']?> custom"></i></span>
						</div>
                </div>
            </div>
	                        
    <h4 class="<?=$statusBar[$status]['border']?> font-weight-bold mb-3 mt-5"><?=$nametask?></h4>
    <hr>
    <div class="row">
        <div class="col-5">
		<div class="position-relative deadline-block">
            <div class="progress position-relative mr-1" style="height: 30px; font-size: 14px; z-index: 1; ">
                <div class="progress-bar bg-secondary-custom rounded" role="progressbar" style="width: 5%" aria-valuenow="5%" aria-valuemin="0" aria-valuemax="100"></div>
                <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                <div class="p-1 date-inside">
                    <i class="far fa-calendar-times text-ligther-custom"></i><span class="text-ligther-custom ml-2">Дедлайн: </span><span>31 мая</span>
                </div>
                </medium>
            </div>
            <span class="position-absolute edit"><i class="fas fa-pencil-alt"></i></span>
		</div>
    
        </div>
        <div class="col-7">
            <div class="float-right">
				<img src="/upload/avatar/4.jpg" class="avatar mr-1">
				<span class="mr-1 text-secondary">|</span>
            	<img src="/upload/avatar/2.jpg" class="avatar mr-1">
            	<img src="/upload/avatar/4.jpg" class="avatar mr-1">
            </div>
        </div>
    </div>
	                        
                            
        <div class="mt-5 mb-5 text-justify"><?=$description?></div>
        
        <?php if (count($files) > 0): ?>
            <?php foreach ($files as $file): ?>
                <?php if ($file['is_deleted']): ?>
                    <p class="text-secondary"><s><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></s> (удален)</p>
                <?php else: ?>
                    <p class="text-secondary"><a class="text-secondary" href="../../<?= $file['file_path'] ?>"><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a></p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
            
        <div id="control">
            <?php
                include 'engine/backend/task/task/control/'.$role.'/'.$status.'.php';
                include 'engine/frontend/task/control/'.$role.'/'.$status.'.php';
            ?>
        </div>
    </div>  
</div>
</div>
</div>

<div class="card mt-3">
	<div class="container-fluid">
		<div class="card-body">
				<div class="col-12">
					<div class="d-flex comin">
						<input class="form-control mr-3" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
		                    
		                <button type="submit" class="btn btn-light btn-file mr-3"><i class="fas fa-file-upload custom-date"></i><input id="sendFiless" type="file"></button>
		                
		                <button type="submit" id="comment" class="btn btn-primary" title="<?=$GLOBALS['_send']?>"><i class="fas fa-paper-plane"></i></button>
		            </div>
				</div>
			</div>
	</div>
</div>

<div class="card mt-3">
	<div class="container-fluid">
		<div class="card-body">
			<div class="col-12">
			<?php include 'engine/frontend/task/notyfeed.php' ?>
			</div>
		</div>
	</div>
</div>

<script>
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>
<script src="/assets/js/task.js"></script>
<script src="/assets/js/datepicker.js"></script>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?= $id ?>, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        subscribeToMessagesNotification();
    });
</script>
