<div class="text-center">
<div id="status-block">
	<p class="text-ligther"><?=$GLOBALS["_postpone"]?></p>
	<h1 class="mt-4"><?=$postponedate?></h1>
	<p class="text-primary mb-4"><?=$GLOBALS["_newdate"]?></p>
	<button type="button" id="done" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_completetask"]?></button>
</div>
<div id="report-block" class="d-none">
	<p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
	
	<div class="form-group">
		<textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
		<button type="submit" id="sendonreview" class="btn btn-primary w-100 text-center mt-3"><?=$GLOBALS["_sendpending"]?></button>
	</div>
</div>
</div>