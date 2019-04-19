<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div id="report-block" class="collapse">
    <div class="form-group">
        <p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
        <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
        <div class="form-group row d-block mb-0">
            <div class="col-7">
                <input class="form-control" type="date" value="dateControl" id="example-date-input">
            </div>
            <button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3 mb-1 ml-3" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_back"]?></button>
            <button type="submit" id="sendonreview" class="btn btn-success w-50 text-center mt-3 mb-1"><?=$GLOBALS["_return"]?></button>
            <span class="btn btn-light btn-file mt-3 mb-1">
                <i class="fas fa-file-upload custom-date"></i><input type="file">
            </span>
            <!--        <button type="file" id="attachbutton" class="btn btn-light w-30 text-center mt-3"><i class="fas fa-file-upload custom-date"></i></button>-->
        </div>
    </div>
</div>


<div id="status-block">
    <div class="postpone-manager">
        <?=$workername?> <?=$workersurname?> запрашивает перенос срока на дату <?=$postponedate?>
        <div>
            Причина:
            <?=$request?>
        </div>
        <div class="pl-2"><i class="fas fa-check custom-date accept" id="confirmDate"></i><i class="fas fa-times custom-date cancel" id="cancelDate"></i></div>
    </div>
    <button id ="return-manager" type="button" class="btn btn-warning mt-3 mb-1 w-10"   data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_return"]?></button>
    <button id="workdone" type="button" class="btn btn-success mt-3 mb-1"><?=$GLOBALS["_done"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-1"><?=$GLOBALS["_cancel"]?></button>
</div>


<script>

</script>
<script src="/assets/js/datepicker.js"></script>