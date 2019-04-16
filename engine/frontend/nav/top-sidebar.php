<div class="col-sm-12 mt-3">
	<div class="float-left">
		<?php $namec = $GLOBALS["namec"]; ?>
		<a class="navbar-brand text-uppercase font-weight-bold mb-3 visible-lg" href="/"><?=$namec?></a>
	</div>
	<div class="float-right">
		<a href="/profile/<?=$id?>/" style="display: none;" class="mr-1"><?=avatartop()?><span class="badge badge-light"><?=fiotop()?></span></a>
		<a href="/mail/" class="mr-3"><i class="fas fa-envelope text-success"></i></a>
		<a href="/log/" class="mr-3"><i class="fas fa-bell text-primary"></i></a>
		<a href="/logout/"><i class="fas fa-sign-out-alt"></i></a>	
	</div>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
</div>