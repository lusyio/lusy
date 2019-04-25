

<div class="postpone-manager">
    <div class="report">
        <h4 class="text-ligther">Отчет:</h4>
        <p><?=$report?></p>
        <?php if (count($files) > 0): ?>
            <p class="">Прикрепленнные файлы:</p>
            <?php foreach ($files as $file): ?>
                <p><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
            <?php endforeach; ?>
        <?php endif; ?>
        <span class="text-ligther align-center mb-0">Задача на рассмотрении с <?=$pendingdate?></span>
    </div>
</div>


<div id="report-block" class="collapse">
    <div class="form-group">
        <p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
        <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
        <input class="form-control" type="date" id="example-date-input" value="" min="">
        <div class="form-group row d-block mb-0">
            <button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3 mb-1 ml-3" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_back"]?></button>
            <button type="submit" id="workreturn" class="btn btn-success w-30 text-center mt-3 mb-1"><?=$GLOBALS["_return"]?></button>
            <span class="btn btn-light btn-file mt-3 mb-1">
                <i class="fas fa-file-upload custom-date"></i><input type="file">
            </span>
        </div>
    </div>
</div>


<div id="status-block">
    <button id ="workdone" type="button" class="btn btn-success mt-3 mb-3 w-10"><?=$GLOBALS["_done"]?></button>
    <button id ="return-manager" type="button" class="btn btn-warning mt-3 mb-3 w-10"  data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_return"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-3 w-10"><?=$GLOBALS["_cancel"]?> <i class="fas fa-times cancel" id="cancel-icon-button"></i></button>
</div>


<script>

</script>