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
        'border' => 'border-secondary',
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
                    <div class="card mb-5">
                        <div class="card-body">
                            <h2 class="<?=$statusBar[$status]['border']?>"><?=$nametask?></h2>
                            <p class="text-ligther"><?=$viewState?></p>
                            <h6 class="text-ligther"><i class="far fa-calendar-times custom"> </i> <?=$datedone?> <?=$datepostpone?> <?=$GLOBALS["_$status"]?> </h6>
                            <div class="mt-5 mb-5 text-justify"><?=$description?></div>
                            <div class="mt-5 mb-5 text-justify">
                                <?php if (count($files) > 0): ?>
                                    <p class="">Прикрепленнные файлы:</p>
                                    <?php foreach ($files as $file): ?>
                                        <?php if ($file['is_deleted']): ?>
                                            <p class=""><s><?= $file['file_name'] ?></s> (удален)</p>
                                        <?php else: ?>
                                            <p><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
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
                  <?php include 'engine/frontend/task/notyfeed.php' ?>
              
</div>


<script>
var $usp = <?php echo $id + 345;  // айдишник юзера ?>; var $it = '<?=$idtask?>';
</script>


<script src="/assets/js/task.js"></script>
<script src="/assets/js/datepicker.js"></script>
