

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div class="postpone-manager">
    <?=$workername?> <?=$workersurname?> запрашивает перенос срока на дату <?=$postponedate?>
    <div>
        Причина:
        <?=$request?>
    </div>
</div>

<div id="report-block" class="collapse">
    <p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
    <div class="form-group mb-0">
        <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
        <button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_back"]?></button>
        <button type="submit" id="sendonreview" class="btn btn-success w-30 text-center mt-3 mb-1"><?=$GLOBALS["_sendpending"]?></button>
        <span class="btn btn-light btn-file mt-3 mb-1">
            <i class="fas fa-file-upload custom-date"></i><input type="file">
        </span>
    </div>
</div>


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_completetask"]?></button>
</div>


<script>

</script>