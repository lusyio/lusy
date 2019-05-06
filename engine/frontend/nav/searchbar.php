<h3 class="pb-3"><?=$GLOBALS["_tasks"]?></h3>
<input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2" type="text" placeholder="<?=$GLOBALS["_searchplaceholder"]?>...">

<div class="row">
    <div class="col-sm-12 mb-2">

            <div class="d-inline-block mb-2">
                <?php if($isManager): ?>
                <div id="managerSearch" rol="manager" class="btn btn-secondary words-search role-search">
                    <span><?=$GLOBALS["_managerfilter"]?></span>
                </div>
                <?php endif; ?>
                <?php if($isWorker): ?>
                <div id="workerSearch" rol="worker" class="btn btn-secondary words-search role-search">
                    <span><?=$GLOBALS["_workerfilter"]?></span>
                </div>
                <?php endif; ?>
                <?php foreach ($usedStatuses as $status): ?>
                    <div id="<?= $status[0] ?>Search" rel="<?= $status[0] ?>" class="btn btn-secondary words-search status-search">
                        <span><?=$GLOBALS["_{$status[0]}filter"]?></span>
                    </div>
                <?php endforeach; ?>

                <div id="resetSearch" class="btn btn-secondary words-search-reset">Сброс<i class="fas fa-times ml-1"></i></div>
            </div>
    </div>
</div>