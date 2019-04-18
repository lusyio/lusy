

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<!--<div id="postpone-block">-->
<!--	<p class="text-ligther">--><?//=$GLOBALS["_postponenew"]?><!--</p>-->
<!--	<div class="form-group">-->
<!--		<input type="date" id="datepostpone" min="--><?//=$GLOBALS["now"]?><!--" value="--><?//=$GLOBALS["now"]?><!--" class="form-control">-->
<!--		<button type="submit" id="sendpostpone" class="btn btn-primary mt-3 mb-3">--><?//=$GLOBALS["_postponebutton"]?><!--</button>-->
<!--	</div>-->
<!--</div>-->



<div id="status-block">
    <button id ="workdone" type="button" class="btn btn-success mt-3 mb-3 w-10"><?=$GLOBALS["_done"]?></button>
    <button type="button" id="cancelTask" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')"><?=$GLOBALS["_cancel"]?></button>
    <input type="text" id="minMaxExample" class='datepicker-here' placeholder="Перенести срок" />
</div>




<script>

</script>
<script src="/assets/js/datepicker.js"></script>