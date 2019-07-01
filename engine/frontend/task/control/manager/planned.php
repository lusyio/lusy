

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div class="postpone-manager" id="inwork-postpone-manager">
    <div class="report">
        <h4 class="text-ligther"><?=$note?></h4>
        <p></p>
    </div>
</div>


<div id="status-block">
    <button id="changePlanDate" type="button" class="btn btn-outline-primary mt-3 mb-3 w-10"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_changePlanDate"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-3 w-10"><i class="fas fa-times cancel mr-2" id="cancel-icon-button"></i> <?=$GLOBALS["_cancel"]?></button>
</div>
