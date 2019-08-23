

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div class="postpone-manager">
    <p class="text-ligther">
        <?php if ($worker == $id): ?>
    Вы запросили перенос срока на дату <?=$postponedate?>
        <?php else: ?>
    <?=$workerName?> запрашивает перенос срока на дату <?=$postponedate?>
        <?php endif; ?>
    </p>
    <div>
        <h5>Причина:</h5>
        <?=htmlspecialchars_decode($request)?>
    </div>
</div>

<div id="report-block" class="collapse">
    <p class="font-weight-bold mb-3"><?=$GLOBALS["_writereport"]?>:</p>
    <div class="form-group mb-0 drag-n-drop">
        <div class="row">
            <div class="col">
                <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
            </div>
        </div>
        <div class="bg-white file-name-review container-files display-none">
        </div>
        <div class="row">
            <div class="col-3 col-lg-1 mt-2">
                <?php $uploadModule = 'task'; ?>
                <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>
            </div>
            <div class="col-4 col-lg-4 mt-2 pr-0">
                <button type="button" id="backbutton" class="btn btn-outline-secondary w-100 text-center" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-angle-double-left mr-3 icon-btn-taskblock"></i><?= $GLOBALS["_back"] ?></button>
            </div>
            <div class="col-5 col-lg-6 mt-2">
                <button type="submit" id="sendonreview" class="btn btn-primary w-100 text-center"><i class="fas fa-file-import mr-3 icon-btn-taskblock"></i>Отправить на рассмотрение</button>
            </div>
        </div>
    </div>
</div>


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
</div>


<script>

</script>
