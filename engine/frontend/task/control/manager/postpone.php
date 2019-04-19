

<link href="/assets/css/datepicker.min.css" rel="stylesheet" type="text/css">


<div id="status-block">
    <div class="postpone-manager">
        <?=$workername?> <?=$workersurname?> запрашивает перенос срока на дату <?=$postponedate?>
        <div>
            Причина:
            <?=$request?>
        </div>
        <div class="pl-2">
            <i class="fas fa-check custom-date accept" id="confirmDate"></i><i class="fas fa-times custom-date cancel" id="cancelDate"></i>
        </div>
    </div>
    <button id="workdone" type="button" class="btn btn-success mt-3 mb-1"><?=$GLOBALS["_done"]?></button>
    <button id="cancelTask" type="button" class="btn btn-outline-danger mt-3 mb-1"><?=$GLOBALS["_cancel"]?></button>
</div>


<script>
</script>
