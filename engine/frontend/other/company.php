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
                <div class="col-sm-5">
                    <div class="d-flex">
                        <p class="font-weight-bold mr-3">#<?= $i ?></p>
                        <div class="user-pic position-relative" style="width:85px">
                            <a href="/profile/'.$id.'/"><img src="/<?=getAvatarLink($n["id"]) ?>" class="avatar-img rounded-circle w-100 mb-4"/></a></div>
                        <p class="ml-3 mt-4"><a
                                    href="/profile/<?= $n["id"] ?>/"><?= $n["name"] ?> <?= $n["surname"] ?></a></p>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="ml-3 mt-4">
                        <span>Phone : +7(555)555-5555</span>
                        <br>
                        <span>Email : demo@demo.ru</span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="ml-3 mt-4">
<!--                        <span><i class="fas fa-long-arrow-alt-up edit-profile"></i></span>-->
                    </div>
                </div>
                <div class="col-sm">
                    <div class="float-right">
                        <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>