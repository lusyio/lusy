

<div class="row justify-content-center">
    <div class="col-sm-12 mb-2">
        <div class="col-md-12 card card-sm br-8">
            <div class="card-body row no-gutters align-items-center pb-1">
                <div class="col-auto">
                    <i class="mr-1 fas fa-search custom-date"></i>
                </div>
                <div class="col">
                    <input id="searchInput" class="form-control form-control-sm form-control-borderless" type="text" placeholder="<?=$GLOBALS["_searchplaceholder"]?>">
                </div>
            </div>

            <div class="d-inline-block mb-2 ml-5">
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
            </div>

        </div>
    </div>
</div>