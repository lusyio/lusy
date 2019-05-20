<div class="top-sidebar pt-2 pb-2">
	<div class="container">
		<div class="row">
			<div class="col-sm-3">
				<a class="navbar-brand text-uppercase font-weight-bold visible-lg" href="/"><?=$namec?></a>
		        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>
			</div>
			<div class="col-sm-4">
				<input class="form-control mr-sm-2" id="search" type="text" autocomplete="off" placeholder="Search..." style=" margin-left: 8px; ">
			</div>
			<div class="col-sm-5">
				<div class="float-right text-right alerts">
					<a href="/mail/" class="mr-3 text-decoration-none"><i class="fas fa-envelope <?= ($newMailCount) ? 'text-warning' : '' ?>" id="messagesIcon"></i> <strong class="text-warning" id="messagesCount"><?= ($newMailCount) ? $newMailCount : '' ?></strong></a>
					<a href="/log/" class="mr-3 text-decoration-none"><i class="fas fa-bell <?= ($newLogCount) ? 'text-warning' : '' ?>" id="notificationIcon"></i><strong class="text-warning" id="notificationCount"><?= ($newLogCount) ? $newLogCount : '' ?></strong></a>
                    <a href="/log/" class="mr-3 text-decoration-none"><i class="fas fa-comment  <?= ($newCommentCount) ? 'text-warning' : '' ?>"  id="commentIcon"></i><strong class="text-warning" id="notificationCount"><?= ($newCommentCount) ? $newCommentCount : '' ?></strong></a>
                    <a href="/logout/" class="mr-3"><i class="fas fa-sign-out-alt"></i></a>
					<a href="/profile/<?=$id?>/"><img class="user-img rounded-circle" src="/upload/avatar/<?=$id?>.jpg"/></a>
				</div>
			</div>
		
		</div>
	</div>
</div>