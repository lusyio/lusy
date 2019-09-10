<div class="postpone-manager" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther"><?= $note ?></h4>
        <p></p>
    </div>
</div>


<div id="status-block" class="mt-3">
    <button id="changePlanDate" type="button" class="btn btn-outline-primary mb-3"><i
                class="fas fa-check mr-2"></i>В работу</button>
    <input type="date" class="form-control change-plan-date d-none" id="inputChangePlanDate" min="<?= $GLOBALS["now"] ?>" value="<?= $GLOBALS["now"] ?>" required>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-3 w-10<?= ($hasUnfinishedSubTask) ? ' continue-none' : ''?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя отменить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i
                class="fas fa-times cancel mr-2" id="cancel-icon-button"></i> <?= $GLOBALS["_cancel"] ?></button>
</div>
