

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css" xmlns="http://www.w3.org/1999/html">


<div class="postpone-manager <?=$displayNote?>" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther"><?=$note?></h4>
        <p><?=$request?></p>
    </div>
</div>


<div id="report-block" class="collapse">
	<p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
	<div class="form-group mb-0">
		<textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
		<button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_back"]?></button>
        <button type="submit" id="sendonreview" class="btn btn-outline-primary w-30 text-center mt-3 mb-1"><?=$GLOBALS["_sendpending"]?></button>
        <span class="btn btn-light btn-file mt-3 mb-1">
            <i class="fas fa-file-upload custom-date"></i><input type="file">
        </span>
	</div>
</div>


<!--<div id="change-date" class="collapse">-->
<!--    <div class="form-group">-->
<!--        <div class="col-7">-->
<!--            <textarea name="report" id="reportarea1" class="form-control" rows="4" placeholder="--><?//=$GLOBALS["_report"]?><!--" required></textarea>-->
<!--            <input class="form-control" value="" type="date" id="example-date-input" min="">-->
<!--            <button type="button" id="backbutton1" class="btn btn-secondary w-30 text-center mt-3 mb-1" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">--><?//=$GLOBALS["_back"]?><!--</button>-->
<!--            <button type="submit" id="sendpostpone" class="btn btn-success text-center mt-3 mb-1">--><?//=$GLOBALS["_change"]?><!--</button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<div id="status-block" class="status-block-inwork-worker">
    <button type="button" id="done" class="btn btn-primary mt-3 mb-1" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
    <button type="button" id="changeDate" class="btn btn-warning mt-3 mb-1 d-none" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">Перенести срок</button>
</div>


<script>

</script>

