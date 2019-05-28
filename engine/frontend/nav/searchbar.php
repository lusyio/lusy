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
        <div class="filterSelect position-relative mr-1">
            <span cnt="<?= $countAllTasks ?>"
                  class="selected-role">Актуальные</span>
            <span class="selected-status text-secondary"></span>
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
                <?php foreach ($sortedUsedStatuses as $status => $statusName): ?>
                    <div id="<?= $status ?>Search" rel="<?= $status ?>"
                         class="btn btn-secondary words-search active-other status-search w-100">
                        <span class="status-name"><?= $statusName ?></span>
                        <span class="count"></span>
                    </div>
                <?php endforeach; ?>
                <hr class="m-0">
                <?php if ($countArchiveDoneTasks > 0): ?>
                    <div class="archive-search search-done words-search">
                        <span class="archive-name">Завершенные</span>
                        <span class="done-count">(<?= $countArchiveDoneTasks ?>)</span>
                    </div>
                <?php endif; ?>
                <?php if ($countArchiveCanceledTasks > 0): ?>
                    <div class="archive-search search-cancel words-search">
                        <span class="archive-name">Отмененные</span>
                        <span class="done-count">(<?= $countArchiveCanceledTasks ?>)</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="status-block">
            <span class="status filterSelect new-status text-success"></span>
            <span class="status filterSelect inwork-status text-primary"></span>
            <span class="status filterSelect overdue-status text-danger"></span>
            <span class="status filterSelect postpone-status text-warning"></span>
            <span class="status filterSelect pending-status text-secondary"></span>
            <span class="count-all"></span>
        </div>
        <div id="resetSearch">
            <span class="filterPlace text-ligther pl-0">
                <i class="icon-filter fas fa-times"></i>
            </span>
        </div>

        <!--        <span class="float-right"><i class="fas fa-archive"></i></span>-->
    </div>
</div>
<hr>

<div class="search-container tasks-search-container">
    <div id="searchResult">
        <div class="search-empty">
            <p>По запросу ничего не найдено.</p>
        </div>
    </div>
</div>

<div class="load-archive-page load-done position-absolute">
    <div id="loadArchiveDone" class="rounded-circle btn btn-light"><i class="fas fa-chevron-down"></i></div>
</div>

<div class="load-archive-page load-canceled position-absolute">
    <div id="loadArchiveCanceled" class="rounded-circle btn btn-light"><i class="fas fa-chevron-down"></i></div>
</div>

<script>
    $(document).ready(function () {
        $(".filterSelect").on('click', function () {
            $(".popUpDiv").fadeIn(300);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest(".filterSelect").length) {
                $('.popUpDiv').fadeOut(300);
            }
        });
    });
</script>