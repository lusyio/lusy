<div class="postpone-manager">
    <div class="report">
        <h4 class="text-ligther">Отчет:</h4>
        <p><?= htmlspecialchars_decode($report) ?></p>
        <?php if (count($files) > 0): ?>
            <p class="">Прикрепленнные файлы:</p>
            <?php foreach ($files as $file): ?>
                <p><a class=""
                      href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
        <span class="text-ligther align-center mb-0">Задача на рассмотрении с <?= $pendingdate ?></span>
    </div>
</div>

<div id="report-block" class="collapse review-block mt-2">
    <p class="font-weight-bold mb-3"><?= $GLOBALS["_pendingtext"] ?>:</p>
    <div class="form-group mb-0 drag-n-drop">
        <div class="row">
            <div class="col">
                <textarea name="report" id="reportarea" class="form-control" rows="4"
                          placeholder="<?= $GLOBALS["_pendingareatext"] ?>" required></textarea>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <input class="form-control" type="date" id="returnDateInput" min="<?= $GLOBALS["now"] ?>"
                       value="<?= $GLOBALS["now"] ?>">
            </div>
        </div>
        <div class="bg-white file-name-review container-files display-none">
        </div>
        <div class="d-flex mt-3">
            <div class="mr-3">
                <button type="submit" id="workreturn" class="btn btn-primary w-100 text-center"><i
                            class="fas fa-file-import mr-3"></i><?= $GLOBALS["_return"] ?></button>
            </div>
            <div class="mr-3">
                <?php $uploadModule = 'task'; ?>
                <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>
            </div>
            <div class="">
                <button type="button" id="backbutton" class="btn btn-outline-secondary w-100 text-center"
                        data-toggle="collapse" data-target="#report-block" aria-expanded="true"
                        aria-controls="report-block"><i class="fas fa-angle-double-left mr-3"></i><?= $GLOBALS["_back"] ?></button>
            </div>
        </div>

    </div>
</div>


<div id="status-block" class="mt-3">
    <button id="workdone" type="button"
            class="btn btn-primary mb-3 mr-1 w-10<?= ($hasUnfinishedSubTask) ? ' continue-none' : '' ?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя завершить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-check mr-2"></i> <?= $GLOBALS["_completetask"] ?></button>
    <button id="return-manager" type="button" class="btn btn-outline-warning mr-1 mb-3" data-toggle="collapse"
            data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i
                class="fas fa-exchange-alt mr-2"></i> <?= $GLOBALS["_return"] ?></button>
    <button id="cancelTask" type="button"
            class="btn btn-outline-danger mb-3 w-10<?= ($hasUnfinishedSubTask) ? ' continue-none' : '' ?>"
        <?= ($hasUnfinishedSubTask) ? 'data-toggle="tooltip" data-placement="bottom" title="Нельзя отменить задачу - есть незавершенные подзадачи"' : ''; ?>
    ><i class="fas fa-times cancel mr-2" id="cancel-icon-button"></i> <?= $GLOBALS["_cancel"] ?></button>
</div>


<script>

</script>
