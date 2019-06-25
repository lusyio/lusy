<?php if ($values['got']): ?>
<div class="award mt-3 complete-awards">
    <div>
        <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
             data-value="1.00"></div>
        <div class="award-star bg-primary">
            <i class="fas <?= $badges[$name] ?>"></i>
        </div>
    </div>
    <h6 class="text-uppercase font-weight-bold"><?= $GLOBALS['_' . $name] ?></h6>
    <small class="text-muted mt-2 text-award"><?= $GLOBALS['_' . $name . '_text' ]?></small>
    <hr>
    <span class="badge badge-primary"><?= date('d.m.Y', $values['datetime']) ?></span>
</div>
<?php else: ?>
<div class="award mt-3">
    <div>
        <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 0, 0, .3)&quot;}"
             data-value="<?= $values['value'] ?>"></div>
        <div class="award-star bg-secondary">
            <i class="fas <?= $badges[$name] ?>"></i>
        </div>
    </div>
    <h6 class="text-uppercase font-weight-bold"><?= $GLOBALS['_' . $name] ?></h6>
    <small class="text-muted mt-2 text-award"><?= $GLOBALS['_' . $name . '_text' ]?></small>
    <hr>
    <span class="badge badge-secondary"><?= $values['count'] ?></span>
</div>
<?php endif; ?>
