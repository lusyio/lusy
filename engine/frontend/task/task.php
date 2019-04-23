<?php
$statusBar = [
    'new' => [
        'border' => 'border-success',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'inwork' => [
        'border' => 'border-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'overdue' => [
        'border' => 'border-danger',
        'bg1' => 'bg-danger',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fab fa-gripfire',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'postpone' => [
        'border' => '',
        'bg1' => 'bg-warning',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'far fa-clock',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'pending' => [
        'border' => 'border-warning',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-warning',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'returned' => [
        'border' => 'border-primary',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'done' => [
        'border' => 'border-success',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-success',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'canceled' => [
        'border' => '',
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
};
?>

<div class="container-fluid" id="task">
		<div class="row justify-content-center">
              <div class="col-12 col-lg-10">
                    <div class="card mb-5">
                        <div class="card-body">
                            <h2 class="<?=$statusBar[$status]['border']?>"><?=$nametask?></h2>
                            <p class="text-ligther"><?=$viewState?></p>
                            <h6 class="text-ligther"><i class="far fa-calendar-times custom"></i>  <?=$datedone?> <?=$datepostpone?> <?=$GLOBALS["_$status"]?> </h6>
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
                                <div class="col- ml-3" title="<?=$GLOBALS["_$status"]?>">
                                    <p class="font-weight-bold text-ligther text-uppercase">Статус</p>

                                    <span class="status-icon rounded-circle noty-m <?=$statusBar[$status]['bg1']?>"><i class="<?=$statusBar[$status]['ic1']?> custom"></i></span>
                                    <span class="status-icon rounded-circle noty-m <?=$statusBar[$status]['bg2']?>"><i class="<?=$statusBar[$status]['ic2']?> custom"></i></span>
                                    <span class="status-icon-last rounded-circle noty-m <?=$statusBar[$status]['bg3']?>"><i class="<?=$statusBar[$status]['ic3']?> custom"></i></span>
                                </div>
                                <div class="col- ml-3">
                                    <p class="font-weight-bold text-ligther text-uppercase">Постановщик</p>
                                    <p class="mb-0"><img src="/upload/avatar/<?=$manager?>.jpg" alt="manager image" class="avatar mr-1"> <?=$managername?> <?=$managersurname?></p>
                                </div>
                                <div class="col- ml-3 mr-3">
                                    <p class="font-weight-bold text-ligther text-uppercase">Исполнитель</p>
                                    <p class="mb-0"><img src="/upload/avatar/<?=$worker?>.jpg" alt="worker image" class="avatar mr-1"> <?=$workername?> <?=$workersurname?></p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
<<<<<<< HEAD

<!--                    <nav>-->
<!--                        <div class="nav nav-tabs" id="nav-tab" role="tablist">-->
<!--                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Лента (1)</a>-->
<!--                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Файлы (0)</a>-->
<!--                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Информация</a>-->
=======
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Лента (1)</a>
                            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Файлы (0)</a>
                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Информация</a>
>>>>>>> 7427e896a107cf808d2a4e574db8e79a95587678
<!--                            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-log" role="tab" aria-controls="nav-log" aria-selected="false">Журнал</a>-->
<!--                        </div>-->
<!--                    </nav>-->

                <div class="tab-content bg-white p-3" id="nav-tabContent">
                    <div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="row">
                            <div class="col-sm-12 comin">
                                <input class="form-control" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
                                <button type="submit" id="comment" class="btn btn-primary" title="<?=$GLOBALS['_send']?>"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
<!--                        <div class="center-checkbox">-->
<!--                            <input type="checkbox">Лента-->
<!--                        </div>-->
                        <div class="btn btn-outline-secondary mt-2" id="addFiles">Файлы</div>
                        <div id="comments"></div>
                    </div>
              </div>
	    </div>
</div>


<script>
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>


<script src="/assets/js/task.js"></script>
