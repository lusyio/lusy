<div class="card tasks-search-bar">
    <div class="card-body pb-1">
        <div class="input-group">
            <input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2"
                   type="text" placeholder="<?= $GLOBALS["_searchplaceholder"] ?>...">
            <span class="icon-searchbar"><i class="fas fa-search"></i></span>
        </div>
    </div>
    <div class="d-flex">
        <span class="text-ligther mb-2 filterPlace mr-1">Показывать </span>
        <div class="filterSelect position-relative mr-1" id="filterSelect">
            <span cnt="<?= $countAllTasks ?>"
                  class="selected-role text-primary">Актуальные</span>
            <span class="selected-status text-secondary"></span>
            <span class="count-all"></span>
            <div class="popUpDiv">
                <?php if ($isWorker): ?>
                    <div id="workerSearch" rol="worker" class="btn btn-secondary words-search role-search w-100">
                        <span class="role-name"><?= $GLOBALS["_workerfilter"] ?></span>
                        <span class="count"></span>
                    </div>
                <?php endif; ?>
                <?php if ($isManager): ?>
                    <div id="managerSearch" rol="manager" class="btn btn-secondary words-search role-search w-100">
                        <span class="role-name"><?= $GLOBALS["_managerfilter"] ?></span>
                        <span class="count"></span>
                    </div>
                <?php endif; ?>
                <?php foreach ($usedStatuses as $status): ?>
                    <div id="<?= $status[0] ?>Search" rel="<?= $status[0] ?>"
                         class="btn btn-secondary words-search active-other status-search w-100">
                        <span class="status-name"><?= $GLOBALS["_{$status[0]}filter"] ?></span>
                        <span class="count"></span>
                    </div>
                <?php endforeach; ?>
                <!--                <hr class="m-0">-->
                <!--                <div class="archive-search">-->
                <!--                    <span class="archive-name">Archive</span>-->
                <!--                </div>-->
            </div>
        </div>
        <span class="filterPlace text-ligther pl-0"><span id="resetSearch" class="text-danger"><i
                        class="icon-filter fas fa-times"></i></span></span>
        <!--        <span class="float-right"><i class="fas fa-archive"></i></span>-->
    </div>
</div>
<hr>
<script>
    $(document).ready(function () {
        $("#filterSelect").on('click', function () {
            $(".popUpDiv").fadeIn(300);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest(".filterSelect").length) {
                $('.popUpDiv').fadeOut(300);
            }
        });
    });
</script>