<div class="subtask">
    <div class="subtask-card">
        <?php
        foreach ($parentTasks as $parentTask) { ?>
            <div val="<?php echo $parentTask['id']; ?>" class="select-subtask">
                <div class="row">
                    <div class="col text-left text-area-message ml-2 border-left-tasks <?= $borderColor[$parentTask['status']] ?>">
                        <span class="mb-1 add-coworker-text"><?= $parentTask['name']; ?></span>
                    </div>
                    <div class="col-2">
                        <i class="fas fa-exchange-alt icon-change-responsible"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>