<div class="card tasks-search-bar">
    <div class="card-body pb-1">
        <div class="input-group">
            <input id="searchInput" autocomplete="off" class="form-control form-control-sm form-control-borderless mb-2"
                   type="text" placeholder="<?= $GLOBALS["_searchplaceholder"] ?>...">
            <span class="icon-searchbar"><i class="fas fa-search"></i></span>
        </div>
    </div>
    <div class="d-inline-flex">
        <div id="filterSelect" class="position-relative mr-1 mb-2">
            <span class="text-ligther">Показывать </span>
            <span class="filter-select selected-role actual">Актуальные</span>
            <span class="filter-select in selected-role "></span>
            <span class="filter-select out selected-role "></span>
            <span class="selected-status text-secondary"></span>
            <div class="popUpDiv">
                <div id="actualSearch" class="words-search w-100 active">
                    <span class="role-name">Актуальные</span>
                    <span class="count"> (<?= $countAllTasks ?>)</span>
                </div>
                <?php if ($isWorker): ?>
                    <div id="workerSearch" rol="worker" class="words-search role-search w-100">
                        <span class="role-name"><?= $GLOBALS["_workerfilter"] ?></span>
                        <span class="count"></span>
                    </div>
                <?php endif; ?>
                <?php if ($isManager): ?>
                    <div id="managerSearch" rol="manager" class="words-search role-search w-100">
                        <span class="role-name"><?= $GLOBALS["_managerfilter"] ?></span>
                        <span class="count"></span>
                    </div>
                <?php endif; ?>
                <?php foreach ($sortedUsedStatuses as $status => $statusName): ?>
                    <div id="<?= $status ?>Search" rel="<?= $status ?>"
                         class="words-search active-other status-search w-100">
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
            <span class="status archive"
            <span class="status new-status"></span>
            <span class="status inwork-status"></span>
            <span class="status overdue-status"></span>
            <span class="status postpone-status"></span>
            <span class="status pending-status"></span>
            <span class="count-all"></span>
        </div>
        <div class="icon-reset-searchbar">
            <div id="resetSearch">
            <span class="filterPlace text-ligther pl-0">
                <i class="icon-filter fas fa-times"></i>
            </span>
            </div>
        </div>
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
        $("#filterSelect").on('click', '.filter-select, .archive', function () {
            $(".popUpDiv").fadeToggle(200);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest("#filterSelect").length) {
                $('.popUpDiv').fadeOut(200);
            }
        });
    });
</script>