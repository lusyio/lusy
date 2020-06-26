<div class="subtask">
    <div class="subtask-card">
        <div class="empty-list-subtask text-muted text-center">
            Список пуст
        </div>
        <?php foreach ($parentTasks as $parentTaskItem): ?>
            <?php if ($taskEdit && $parentTaskItem['id'] == $taskId) continue; ?>
            <div val="<?php echo $parentTaskItem['id']; ?>" class="select-subtask <?= ($taskEdit && $parentTaskItem['id'] == $parentTask) ? 'd-none' : '' ?>">
            <div class="row">
                <div class="col pr-0 text-left text-area-message ml-2 border-left-tasks <?= $borderColor[$parentTaskItem['status']] ?>">
                    <span class="mb-1 add-coworker-text"><?= $parentTaskItem['name']; ?></span>
                </div>
                <div class="col-2 pl-0">
                    <i class="fas fa-exchange-alt icon-change-responsible"></i>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
