<div class="mb-4 system <?= ($isNew)? 'bg-success':'' ?>" id="<?= $c['id'] ?>">
    <div class="row justify-content-center">
        <div class="col-8 text-center system-text">
            <div class="position-relative">
                <span class="mb-1"><a href="/profile/<?= $c['iduser'] ?>/"><?= $nameuser ?> <?= $surnameuser ?></a></span>
                <span class="mb-2"><?= nl2br($c['comment']) ?></span>
            </div>
        </div>
    </div>
</div>
