

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<!--<div id="postpone-block">-->
<!--	<p class="text-ligther">--><?//=$GLOBALS["_postponenew"]?><!--</p>-->
<!--	<div class="form-group">-->
<!--		<input type="date" id="datepostpone" min="--><?//=$GLOBALS["now"]?><!--" value="--><?//=$GLOBALS["now"]?><!--" class="form-control">-->
<!--		<button type="submit" id="sendpostpone" class="btn btn-primary mt-3 mb-3">--><?//=$GLOBALS["_postponebutton"]?><!--</button>-->
<!--	</div>-->
<!--</div>-->

<div id="change-date" class="collapse">
    <div class="form-group">
        <div class="col-7">
            <input class="form-control" type="date" value="dateControl" id="example-date-input">
            <button type="button" id="backbutton1" class="btn btn-secondary w-30 text-center mt-3" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date"><?=$GLOBALS["_back"]?></button>
            <button type="submit" id="sendDate" class="btn btn-success text-center mt-3"><?=$GLOBALS["_send"]?></button>
        </div>
    </div>
</div>



<div id="status-block">
    <button id ="workdone" type="button" class="btn btn-success mt-3 mb-3 w-10"><?=$GLOBALS["_done"]?></button>
    <button type="button" id="cancelTask" class="btn btn-outline-danger" onclick="return confirm('Are you sure?')"><?=$GLOBALS["_cancel"]?></button>
    <button type="button" id="changeDate" class="btn btn-warning" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date"><?=$GLOBALS["_postponebutton"]?></button>
</div>




<script>

</script>
<script src="/assets/js/datepicker.js"></script>