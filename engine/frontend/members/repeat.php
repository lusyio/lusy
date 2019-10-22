<div class="repeat">
    <div class="repeat-card">
        <?php foreach ($repeatOptions as $key => $name): ?>
            <div val="<?= $key ?>" class="select-repeat">
                <div class="row">
                    <div class="col pr-0 text-left text-area-message ml-2">
                        <span class="mb-1 add-coworker-text"><?= $name ?></span>
                    </div>
                    <div class="col-2 pl-0">
                        <i class="fas fa-exchange-alt icon-change-responsible"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
