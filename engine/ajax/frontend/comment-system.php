<div class="mt-5 mb-5 <?= ($isNew)? 'bg-success':'' ?>" id="<?= $c['id'] ?> ">
    <div class="text-center system text-secondary position-relative">
	    <div class="system-text">
	        <span><a href="/profile/<?= $c['iduser'] ?>/"><?= $nameuser ?> <?= $surnameuser ?></a> <?= nl2br($c['comment']) ?></span>
	    </div>
    </div>
</div>
