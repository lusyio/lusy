

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
    <p class="font-weight-bold"><?=$GLOBALS["_writereport"]?>:</p>
    <div class="form-group mb-2">
        <textarea name="report" id="reportarea" class="form-control mb-3" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
        <button type="submit" id="sendonreview" class="btn btn-primary w-30 text-center mr-3"><i class="fas fa-file-export mr-3"></i><?=$GLOBALS["_sendpending"]?></button>
        <span class="btn btn-light btn-file mr-3">
            <i class="fas fa-file-upload custom-date"></i><input type="file">
        </span>
        <button type="button" id="backbutton" class="btn btn-outline-secondary w-30 text-center" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-angle-double-left mr-3"></i><?= $GLOBALS["_back"] ?></button>

    </div>
</div>


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
</div>


<script>

</script>
