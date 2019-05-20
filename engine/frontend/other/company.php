<div class="card mb-3">
    <div class="card-body text-center">
        <div>
            <h2 class="d-inline text-uppercase font-weight-bold"><?= $namecompany ?></h2>
            <a class="d-inline float-right" href="/invite/"><i class="fas fa-user-plus icon-company"></i></a>
        </div>
    </div>
</div>
<div class="card">
    <?php $i = 0;
    foreach ($sql as $n) {
        $i++; ?>
        <div class="card-body border-bottom">
            <div class="row">
                <div class="col-5">
                    <div class="d-flex">
                        <p class="font-weight-bold mr-3">#<?= $i ?></p>
                        <?= userpic($n["id"]) ?>
                        <p class="ml-3 mt-4"><a
                                    href="/profile/<?= $n["id"] ?>/"><?= $n["name"] ?> <?= $n["surname"] ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>