<div id="status-block">
    <div class="postpone-manager">
        <p class="text-ligther">
        <?=$workerName?> запрашивает перенос срока на дату <?=$postponedate?>
        </p>
        <div>
            <h5>Причина:</h5>
            <?=htmlspecialchars_decode($request)?>
        </div>
        <div>
            <i data-toggle="tooltip" data-placement="bottom" title="Принять перенос" class="fas fa-check custom-date accept" id="confirmDate"></i><i data-toggle="tooltip" data-placement="bottom" title="Отклонить перенос" class="fas fa-times custom-date cancel" id="cancelDate"></i>
        </div>
    </div>
    <button id="workdone" type="button" class="btn btn-outline-primary mt-3 mb-1<?= ($hasUnfinishedSubTask) ? ' continue-none' : ''?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя завершить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-1<?= ($hasUnfinishedSubTask) ? ' continue-none' : ''?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя отменить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-times cancel mr-2" id="cancel-icon-button"></i> <?=$GLOBALS["_cancel"]?></button>
</div>