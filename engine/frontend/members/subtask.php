<div class="subtask" style="bottom: 100px;">
    <div class="subtask-card">
        <?php
        require_once __ROOT__ . '/engine/backend/other/tasks.php';
        foreach ($tasks as $n) { ?>
            <div val="<?php echo $n['idtask'] ?>" class="select-subtask">
                <div class="row">
                    <div class="col-2">
<!--                        <img src="/--><?//= getAvatarLink($n["id"]) ?><!--" class="avatar-added mr-1">-->
                    </div>
                    <div class="col text-left">
                        <span class="mb-1 add-coworker-text"><?= $n['name'] ?></span>
                    </div>
                    <div class="col-2">
                        <i class="fas fa-exchange-alt icon-change-responsible"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>