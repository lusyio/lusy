

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div class="report">
    <h4 class="text-ligther">Причина возврата:</h4>
    <p><?=$request?></p>
</div>


<div id="change-date" class="collapse">
    <div class="form-group">
        <div class="col-7">
            <input class="form-control" type="date" id="example-date-input" value="" min="">
            <button type="button" id="backbutton1" class="btn btn-secondary w-30 text-center mt-3" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date"><?=$GLOBALS["_back"]?></button>
            <button type="submit" id="sendDate" class="btn btn-success text-center mt-3"><?=$GLOBALS["_send"]?></button>
        </div>
    </div>
</div>


<div id="status-block">
    <button id ="workdone" type="button" class="btn btn-success mt-3 mb-3 w-10"><?=$GLOBALS["_done"]?></button>
    <button type="button" id="changeDate" class="btn btn-warning mt-3 mb-3 w-10" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date"><?=$GLOBALS["_postponebutton"]?></button>
    <button type="button" id="cancelTask" class="btn btn-outline-danger mt-3 mb-3 w-10" onclick="return confirm('Are you sure?')"><?=$GLOBALS["_cancel"]?></button>
</div>


<script>

</script>