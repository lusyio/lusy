

<div class="col-sm-12 mt-3">

	<div class="col-sm-2 float-left">
		<?php $namec = $GLOBALS["namec"]; ?>
		<a class="navbar-brand text-uppercase font-weight-bold mb-3 visible-lg" href="/"><?=$namec?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
	</div>
	<div class="col-sm-1 float-right">
		<a href="/profile/<?=$id?>/" style="display: none;" class="mr-1"><?=avatartop()?><span class="badge badge-light"><?=fiotop()?></span></a>
		<a href="/mail/" class="mr-3"><i class="fas fa-envelope text-success"></i></a>
		<a href="/log/" class="mr-3"><i class="fas fa-bell text-primary"></i></a>
		<a href="/logout/"><i class="fas fa-sign-out-alt"></i></a>	
	</div>
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

</div>