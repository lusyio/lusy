

<div class="row justify-content-center">
    <div class="col-sm-10 mb-2">
        <div class="col-md-10 card card-sm br-8">
            <div class="card-body row no-gutters align-items-center pb-1">
                <div class="col-auto">
                    <i class="mr-1 fas fa-search custom-date"></i>
                </div>
                <div class="col">
                    <input id="searchInput" class="form-control form-control-sm form-control-borderless" type="text" placeholder="<?=$GLOBALS["_searchplaceholder"]?>">
                </div>
            </div>

            <div class="d-inline-block mb-2 ml-5">
                <div id="inworkSearch" rel="inwork" class="btn btn-secondary words-search status-search">
                    <span><?=$GLOBALS["_inworkfilter"]?></span>
                </div>
                <div id="pendingSearch" rel="pending" class="btn btn-secondary words-search status-search">
                    <span><?=$GLOBALS["_pendingfilter"]?></span>
                </div>
                <div id="postponeSearch" rel="postpone" class="btn btn-secondary words-search status-search">
                    <span><?=$GLOBALS["_postponefilter"]?></span>
                </div>
                <div id="managerSearch" rol="manager" class="btn btn-secondary words-search role-search">
                    <span><?=$GLOBALS["_managerfilter"]?></span>
                </div>
                <div id="workerSearch" rol="worker" class="btn btn-secondary words-search role-search">
                    <span><?=$GLOBALS["_workerfilter"]?></span>
                </div>
            </div>

        </div>
    </div>
</div>