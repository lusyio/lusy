<div class="mb-4 system <?= ($isNew)? 'bg-success':'' ?>" id="<?= $c['id'] ?>">
    <div class="row">
        <div class="col-1">
            <img src="/upload/avatar/<?= $c['iduser'] ?>.jpg" class="avatar mt-1">
        </div>
        <div class="col-11">
            <div class="position-relative">
				<span class="date">
					<?= $dc ?>
				</span>
                <p class="mb-1"><a href="/profile/<?= $c['iduser'] ?>/" class="font-weight-bold"><?= $nameuser ?> <?= $surnameuser ?></a></p>
                <p class="mb-2"><?= nl2br($c['comment']) ?></p>
            </div>
        </div>
    </div>
</div>
