<!--<div class="text-center" id="f1">-->
<!--<p class="text-ligther">Задача на рассмотрении с --><?//=$pendingdate?><!--</p>-->
<!--<h3 class="mt-4 mb-4">--><?//=$GLOBALS["_pending"]?><!--</h3>-->

<span class="text-ligther align-center mb-0">Задача на рассмотрении с <?=$pendingdate?></span>

    <div id="report-block" class="collapse">
        <p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
        <div class="form-group">
            <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
            <button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block">Back</button>
            <button type="submit" id="sendonreview" class="btn btn-success w-50 text-center mt-3"><?=$GLOBALS["_return"]?></button>
            <!--        <button type="file" id="attachbutton" class="btn btn-light w-30 text-center mt-3"><i class="fas fa-file-upload custom-date"></i></button>-->
            <span class="btn btn-light btn-file">
            <i class="fas fa-file-upload custom-date"></i><input type="file">
        </span>
        </div>
    </div>

    <div id="status-block" class="">
        <button id ="return-manager" class="btn btn-warning mt-3 mb-3 w-10"  data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block">Вернуть</button>
        <button id ="workdone" class="btn btn-success mt-3 mb-3 w-10"><?=$GLOBALS["_done"]?></button>
        <button type="button" id="done" class="btn btn-danger" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block">Отменить задачу</button>
    </div


<script>

</script>
<script src="/assets/js/datepicker.js"></script>
