

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div class="postpone-manager <?=$displayNote?>" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther"><?=$note?></h4>
        <p><?=$request?></p>
    </div>
</div>


<!--<div id="change-date" class="collapse">-->
<!--    <div class="form-group">-->
<!--        <div class="col-7">-->
<!--            <input class="form-control" type="date" id="example-date-input" value="" min="">-->
<!--            <button type="button" id="backbutton1" class="btn btn-secondary w-30 text-center mt-3" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">--><?//=$GLOBALS["_back"]?><!--</button>-->
<!--            <button type="submit" id="sendDate" class="btn btn-outline-primary text-center mt-3">--><?//=$GLOBALS["_send"]?><!--</button>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<div id="status-block">
    <button id ="workdone" type="button" class="btn btn-outline-primary mt-3 mb-3 w-10"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
<!--    <button type="button" id="changeDate" class="btn btn-warning mt-3 mb-3 w-10" data-toggle="collapse" data-target="#change-date" aria-expanded="true" aria-controls="change-date">--><?//=$GLOBALS["_postponebutton"]?><!--</button>-->
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-3 w-10"><?=$GLOBALS["_cancel"]?> <i class="fas fa-times cancel" id="cancel-icon-button"></i></button>
</div>


<script>

</script>