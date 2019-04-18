<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">

<!--
<div id="postpone-block" class="d-none">
	<p class="text-ligther"><?=$GLOBALS["_postponenew"]?></p>
	<div class="form-group">
		<input type="date" id="datepostpone" min="<?=$GLOBALS["now"]?>" value="<?=$GLOBALS["now"]?>" class="form-control">
		<button type="submit" id="sendpostpone" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_postponebutton"]?></button>
	</div>
</div>
-->

<div id="report-block" class="collapse">
	<p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
	<div class="form-group">
		<textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
		<button type="button" id="backbutton" class="btn btn-secondary w-30 text-center mt-3" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_back"]?></button>
        <button type="submit" id="sendonreview" class="btn btn-success w-50 text-center mt-3"><?=$GLOBALS["_sendpending"]?></button>
<!--        <button type="file" id="attachbutton" class="btn btn-light w-30 text-center mt-3"><i class="fas fa-file-upload custom-date"></i></button>-->
        <span class="btn btn-light btn-file">
            <i class="fas fa-file-upload custom-date"></i><input type="file">
        </span>
	</div>
</div>

<div id="status-block">
    <button type="button" id="done" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block"><?=$GLOBALS["_completetask"]?></button>
	<input type="text" id="minMaxExample" class='datepicker-here' placeholder="Перенести срок" />
<!--    <button id="datepicker-button" class="btn btn-light custom-date-button"><i class="far fa-calendar-alt custom-date"></i></button>-->
</div>
<!--<button type="button" id="done" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#report-block" aria-expanded="true" aria-controls="report-block">--><?//=$GLOBALS["_completetask"]?><!--</button>-->
<!--<input type="text" id="minMaxExample" class='datepicker-here' placeholder="Перенести срок" />-->

<script>

</script>
<script src="/assets/js/datepicker.js"></script>
