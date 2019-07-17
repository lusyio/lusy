

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html">


<div class="postpone-manager <?=$displayNote?>" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther"><?=$note?></h4>
        <p><?=htmlspecialchars_decode($request)?></p>
    </div>
</div>

<div id="report-block" class="collapse review-block mt-2">
	<p class="text-ligther mb-2"><?=$GLOBALS["_writereport"]?>:</p>
	<div class="form-group mb-0">
        <div class="row">
            <div class="col">
		        <textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
            </div>
        </div>
        <div style="display: none" class="bg-white file-name-review container-files">
        </div>
        <div class="row mb-1">
            <div class="col-2 col-lg-1 mt-2">
                <?php $uploadModule = 'task'; ?>
                <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>
            </div>
            <div class="col-4 col-lg-2 mt-2">
                <button type="button" id="backbutton" class="btn btn-secondary w-100 text-center" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block" style="height: 38px"><?=$GLOBALS["_back"]?></button>
            </div>
            <div class="col-6 col-lg-5 pl-0 mt-2">
                <button type="submit" id="sendonreview" class="btn btn-outline-primary w-100 text-center">На рассмотрение</button>
            </div>
        </div>

	</div>
</div>


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
    <button type="button" id="changeDate" class="btn btn-warning mt-3 mb-1 d-none" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">Перенести срок</button>
</div>


<script>

</script>

