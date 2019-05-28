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
    foreach ($sql as $n):
        $i++;
        if ($n['is_fired'] != 0 && !$isFiredShown):
            $isFiredShown = true; ?>
            <a href="#" id="showFired" class="text-decoration-none text-center">
                <div class="card-body border-bottom">
                    Уволенные сотрудники
                </div>
            </a>
        <?php endif; ?>
        <div class="card-body border-bottom <?= ($n['is_fired'] == 0) ? '' : 'fired d-none text-muted' ?>">
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
                        <span>Phone : <?= $n['phone'] ?></span>
                        <br>
                        <span>Email : <?=$n['email'] ?></span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="ml-3 mt-4">
<!--                        <span><i class="fas fa-long-arrow-alt-up edit-profile"></i></span>-->
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="float-right">
                        <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script>
    $(document).ready(function() {
        $('#showFired').on('click', function (e) {
            e.preventDefault();
            var fired = $('.fired');
            if (fired.hasClass('d-none')) {
                $(this).addClass('text-primary');
                fired.removeClass('d-none');
            } else {
                fired.addClass('d-none');
                $(this).removeClass('text-primary');

            }
        })
    })
</script>