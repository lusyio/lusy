<div class="card tasks-search-bar">
    <div class="card-body pb-1">
        <input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2"
               type="text" placeholder="<?= $GLOBALS["_searchplaceholder"] ?>...">
        <div class="d-flex">
            <span class="text-ligther mb-2 filterPlace mr-1">Показывать </span>
            <div class="filterSelect text-success position-relative mr-1" id="filterSelect">
                <span>Актуальные (<?= $countAllTasks ?>)</span>
                <div class="popUpDiv">
                    <?php if ($isWorker): ?>
                        <div id="workerSearch" rol="worker" class="btn btn-secondary words-search role-search w-100">
                            <span><?= $GLOBALS["_workerfilter"] ?></span>
                            <span class="count"></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($isManager): ?>
                        <div id="managerSearch" rol="manager" class="btn btn-secondary words-search role-search w-100">
                            <span><?= $GLOBALS["_managerfilter"] ?></span>
                            <span class="count"></span>
                        </div>
                    <?php endif; ?>
                    <?php foreach ($usedStatuses as $status): ?>
                        <div id="<?= $status[0] ?>Search" rel="<?= $status[0] ?>"
                             class="btn btn-secondary words-search status-search w-100">
                            <span><?= $GLOBALS["_{$status[0]}filter"] ?></span>
                            <span class="count"></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <span class="filterPlace text-ligther pl-0">задачи <span id="resetSearch" class="text-danger"><i
                            class="fas fa-times ml-1"></i></span></span> </div>
    </div>
</div>
<hr>
<script>
    $(document).ready(function () {
        $("#filterSelect").on('click', function () {
            $(".popUpDiv").fadeToggle(300);
        });
    });
</script>