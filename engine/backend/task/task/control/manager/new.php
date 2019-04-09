<?php
if ($dayost == 0) {
	$text = $GLOBALS["_deadline"];
	$head = $GLOBALS["_lastday"];
} 
if ($dayost > 0) {
	$text = $GLOBALS["_dayost"];
	$head = $dayost . ' ' . $GLOBALS["_days"];
}
if ($dayost < 0) {
	$text = $GLOBALS["_endfast"];
	$head = $GLOBALS["_overdue"];
}
?>
<div class="text-center">

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
	<p class="text-ligther"><?=$text?></p>
	<h1 class="mt-4"><?=$head?></h1>
	<button type="button" id="postpone" class="btn btn-link mb-4"><?=$GLOBALS["_postponebutton"]?></button>
	<button type="button" id="done" class="btn btn-primary mt-3 mb-3"><?=$GLOBALS["_completetask"]?></button>
</div>

</div>