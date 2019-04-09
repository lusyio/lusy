<div class="card mb-3">
	<div class="card-body text-center">
		<h2 class="text-uppercase font-weight-bold"><?=$namecompany?></h2>
	</div>
</div>
<div class="card">
<?php $i=0; foreach ($sql as $n) { $i++;  ?>
	<div class="card-body border-bottom">
		<div class="row">
			<div class="col-5">
				<div class="d-flex">
					<p class="font-weight-bold mr-3">#<?=$i?></p>
					<?=userpic($n["id"])?>
					<p class="ml-3 mt-4"><a href="/profile/<?=$n["id"]?>/"><?=$n["name"]?> <?=$n["surname"]?></a></p>
				</div>
			</div>
		</div>
	</div>
<?  } ?>
</div>