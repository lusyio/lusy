<div class="subtask" style="bottom: 100px;">
    <div class="subtask-card">
        <?php
        foreach ($parentTasks as $parentTask) { ?>
            <div val="<?php echo $parentTask['id']; ?>" class="select-subtask">
                <div class="row">
                    <div class="col-2">
                    </div>
                    <div class="col text-left">
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