

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div id="status-block">
    <div class="postpone-manager">
        <p class="text-ligther">
        <?=$workername?> <?=$workersurname?> запрашивает перенос срока на дату <?=$postponedate?>
        </p>
        <div>
            <h5>Причина:</h5>
            <?=$request?>
        </div>
        <div class="pl-2">
            <i class="fas fa-check custom-date accept" id="confirmDate"></i><i class="fas fa-times custom-date cancel" id="cancelDate"></i>
        </div>
    </div>
    <button id="workdone" type="button" class="btn btn-outline-primary mt-3 mb-1"><i class="fas fa-check mr-2"></i> <?=$GLOBALS["_completetask"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-1"><i class="fas fa-times cancel mr-2" id="cancel-icon-button"></i> <?=$GLOBALS["_cancel"]?></button>
</div>


<script>
</script>
