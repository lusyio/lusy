<?php if ($showNote): ?>
    <div class="postpone-manager <?= $displayNote ?>" id="inwork-postpone-manager">
        <div class="report">
            <h4 class="text-ligther mb-3"><?= $note ?></h4>
            <p><?= htmlspecialchars_decode($request) ?></p>
            <?php include __ROOT__ . '/engine/frontend/other/preview-template.php'; ?>
        </div>
    </div>
<?php endif; ?>
<div id="status-block" class="mt-3">
    <button id="workdone" type="button"
            class="btn btn-outline-primary mb-3 w-10<?= ($hasUnfinishedSubTask) ? ' continue-none' : '' ?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя завершить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-fw fa-check mr-2"></i> <?= $GLOBALS["_completetask"] ?></button>
    <button id="cancelTask" type="button"
            class="btn btn-outline-danger mb-3 w-10<?= ($hasUnfinishedSubTask) ? ' continue-none' : '' ?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя отменить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-fw fa-times cancel mr-2" id="cancel-icon-button"></i> <?= $GLOBALS["_cancel"] ?></button>
    <?php if ($task->get('repeat_type') > 0): ?>
        <button id="cancelRepeat" type="button" class="btn btn-outline-secondary border-0 mb-3 w-10">
            <i class="fas fa-fw fa-calendar-times cancel mr-2" id="cancel-icon-button"></i>Отменить повторение
        </button>
    <?php endif; ?>
</div>
