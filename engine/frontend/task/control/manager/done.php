
<div class="text-center postpone-manager done">
    <h3 class="mt-4 mb-4"><span class="mr-2"><?=$GLOBALS["_donelist"]?></span>   &#x1f389</h3>
    <p class="text-ligther"> <?=$datedone?></p>
</div>
<div id="status-block" class="mt-3">
    <?php if ($task->get('repeat_type') > 0): ?>
        <button id="cancelRepeat" type="button" class="btn btn-outline-secondary mb-3 w-10">
            <i class="fas fa-calendar-times cancel mr-2" id="cancel-icon-button"></i>Отменить повторение</button>
    <?php endif; ?>
</div>
