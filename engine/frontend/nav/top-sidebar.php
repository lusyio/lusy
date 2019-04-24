

<div class="col-sm-12 mt-3">
	<div class="col-sm-2 float-left">
		<?php $namec = $GLOBALS["namec"]; ?>
		<a class="navbar-brand text-uppercase font-weight-bold mb-3 visible-lg" href="/"><?=$namec?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
	</div>
	<div class="col-sm-1 float-right">
		<a href="/profile/<?=$id?>/" style="display: none;" class="mr-1"><?=avatartop()?><span class="badge badge-light"><?=fiotop()?></span></a>
		<a href="/log/" class="mr-3"><i class="fas fa-bell text-primary"></i></a>
		<a href="/logout/"><i class="fas fa-sign-out-alt"></i></a>	
	</div>
    <div class="row justify-content-center">
        <div class="col-sm-10 mb-2">
            <form class="col-md-10 card card-sm br-8">
                <div class="card-body row no-gutters align-items-center">
                    <div class="col-auto">
                        <i class="mr-1 fas fa-search custom-date"></i>
                    </div>

                    <div class="col">
                        <input id="searchInput" class="form-control form-control-sm form-control-borderless" type="text" placeholder="Search topics or keywords">
                    </div>
                </div>
<!--                <div class="checkbox-searsh">-->
<!--                    <input type="checkbox" name="fl-cont" value="inwork" id="inwork" />В работе-->
<!--                </div>-->
            </form>
        </div>
    </div>
<!--    <div>-->
<!--        <form class="form-inline col-sm-8 float-right search-top-sidebar">-->
<!--            <input class="form-control form-control-sm mr-3 w-75" type="text" placeholder="Search" aria-label="Search">-->
<!--            <i class="fas fa-search" aria-hidden="true"></i>-->
<!--        </form>-->
<!--    </div>-->
</div>
