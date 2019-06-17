<script src="/assets/js/circle-progress.min.js"></script>
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-md-10 col-xl-10">
        <div class="card">
            <div class="card-body p-4">
                <div class="float-left mr-4 position-relative">
                    <img class="rounded-circle avatar-profile " id="avatar"
                         src="/<?= getAvatarLink($profileId) ?>"
                         alt="avatar">
                    <span class="online-indicator-profile mobile-online-indicator">
                    <i class="fas fa-circle mr-1 ml-1 onlineIndicator mail <?= ($userData['online']) ? 'text-success' : '' ?>"></i>
                </span>
                </div>
                <div class='header-profile'>
                    <div class="fio-profile"><?= $userData['name'] ?> <?= $userData['surname'] ?></div>
                    <span class="text-muted-reg"><?= ($userData['online']) ? $GLOBALS['_online'] : ((isset($userData['activity'])) ? $GLOBALS['_wasOnline'] . ' ' . date('d.m H:i', $userData['activity']) : '') ?></span>
                    <?php if ($id === $profileId): ?>
                        <div style="position: absolute;
                                    right: 25px;
                                    top: 20px;">
                            <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="custom-profile float-left">
                    <p class="text-secondary mt-3 mb-2">
                        <i class="fas fa-phone mr-3"></i> <?= $userData['phone'] ?>
                    </p>
                    <p class="text-secondary mb-2">
                        <i class="fas fa-envelope mr-3"></i> <?= $userData['email'] ?>
                    </p>

                    <?php if (!is_null($socialNetworks) && count($socialNetworks)): ?>
                        <div class="row">
                            <div class="col">
                                <div class="socials text-reg">
                                    <span>
                                        <?php if (key_exists('vk', $socialNetworks)): ?>
                                            <a href="https://vk.com/<?= $socialNetworks['vk'] ?>" target="_blank"><i
                                                        class="fab fa-vk icon-social mr-3"></i></a>
                                        <?php endif; ?>
                                        <?php if (key_exists('facebook', $socialNetworks)): ?>
                                            <a href="https://facebook.com/<?= $socialNetworks['facebook'] ?>"
                                               target="_blank"><i
                                                        class="fab fa-facebook-square icon-social mr-3"></i></a>
                                        <?php endif; ?>
                                        <?php if (key_exists('instagram', $socialNetworks)): ?>
                                            <a href="https://instagram.com/<?= $socialNetworks['instagram'] ?>"
                                               target="_blank"><i
                                                        class="fab fa-instagram icon-social mr-3"></i></a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!is_null($userData['about']) && $userData['about'] != ''): ?>
                    <div class="clearfix"></div>
                    <hr class="mt-5">
                    <p class="text-justify text-secondary"><?= nl2br($userData['about']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-12 col-lg-10 col-md-10 col-xl-10">
        <div class="card">
            <div class="card-body">
                <h4 class="ml-4">Достижения</h4>
                <hr class="m-0">
                <div class="d-flex">
                    <div class="col-1 mt-3 ml-2 achievement-sm" title="Решебник 1">
                        <div class="circle-sm" data-value="1"></div>
                        <div class="award-star award-sm">
                            <i class="fas fa-star" style="color: white;"></i>
                        </div>
                    </div>
                    <div class="col-1 mt-3 ml-2 achievement-sm" title="Комментатор 1">
                        <div class="circle-sm" data-value="1"></div>
                        <div class="award-star award-sm bg-warning">
                            <i class="fas fa-comments" style="color: white;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.circle-sm').circleProgress({
        size: 45,
        fill: {
            gradient: ["red", "orange"]
        }
    });

    $('.circle-md').circleProgress({
        size: 75,
        fill: {
            gradient: ["red", "orange"]
        }
    });

    $('.circle').circleProgress({
        size: 75,
        fill: {
            gradient: ["red", "orange"]
        }
    });
</script>

