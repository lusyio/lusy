

<div id="postpone-block" class="d-none">
	<p class="text-ligther"><?=$GLOBALS["_postponenew"]?></p>
	<div class="form-group">
		<input type="date" id="datepostpone" min="<?=$GLOBALS["now"]?>" value="<?=$GLOBALS["now"]?>" class="form-control">
		<button type="submit" id="sendpostpone" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_postponebutton"]?></button>
	</div>
</div>

<div id="report-block" class="d-none">
	<p class="text-ligther"><?=$GLOBALS["_writereport"]?>:</p>
	<div class="form-group">
		<textarea name="report" id="reportarea" class="form-control" rows="4" placeholder="<?=$GLOBALS["_report"]?>" required></textarea>
		<button type="submit" id="sendonreview" class="btn btn-primary w-100 text-center mt-3"><?=$GLOBALS["_sendpending"]?></button>
	</div>
</div>

<div id="status-block">
	<button type="button" id="done" class="btn btn-primary"><?=$GLOBALS["_completetask"]?></button>
	<button type="button" id="postpone" class="btn btn-link text-dark"><i class="far fa-calendar-plus"></i> <?=$GLOBALS["_postponebutton"]?></button>
</div>
