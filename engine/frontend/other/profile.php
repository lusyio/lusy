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
                            <a data-toggle="tooltip" data-placement="bottom" title="Изменить профиль" href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="custom-profile float-left">
                    <?php if ($userData['phone'] === '--'): ?>
                        <a class="text-secondary mt-3 mb-2 d-block">
                            <i class="fas fa-phone mr-3"></i> <?= $userData['phone'] ?>
                        </a>
                    <?php else: ?>
                        <a href="tel:<?= $userData['phone'] ?>" class="text-secondary mt-3 mb-2 d-block">
                            <i class="fas fa-phone mr-3"></i> <?= $userData['phone'] ?>
                        </a>
                    <?php endif; ?>
                    <a href="mailto:<?= $userData['email'] ?>" class="text-secondary mb-2 d-block">
                        <i class="fas fa-envelope mr-3"></i> <?= $userData['email'] ?>
                    </a>

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
                <div class="d-flex flex-wrap text-center">
                    <div class="award award-sm  mt-3" data-toggle="tooltip" data-placement="bottom" title="Заполнил профиль">
                        <div>
                            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
                                 data-value="1.00"></div>
                            <div class="award-star bg-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h6 class="text-uppercase font-weight-bold">Знакомство</h6>
                        <hr>
                        <span class="badge badge-primary">20.05.2019</span>
                    </div>
                    <div class="award award-sm  mt-3">
                        <div>
                            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
                                 data-value="1.00"></div>
                            <div class="award-star bg-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h6 class="text-uppercase font-weight-bold">Знакомство</h6>
                        <hr>
                        <span class="badge badge-primary">20.05.2019</span>
                    </div>
                    <div class="award award-sm mt-3">
                        <div>
                            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
                                 data-value="1.00"></div>
                            <div class="award-star bg-primary">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <h6 class="text-uppercase font-weight-bold">Знакомство</h6>
                        <hr>
                        <span class="badge badge-primary">20.05.2019</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-none">
    <div class="achievement-sm mr-2 mt-3" data-toggle="tooltip" data-placement="bottom" title="Новичок">
        <div class="circle-sm" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}" data-value="1"></div>
        <div class="award-star award-sm bg-primary">
            <i class="fas fa-star" style="color: white;margin-right: 1px;"></i>
        </div>
    </div>
    <div class="achievement-sm mr-2 mt-3" data-toggle="tooltip" data-placement="bottom" title="Начало карьеры">
        <div class="circle-sm" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}" data-value="1"></div>
        <div class="award-star award-sm bg-primary">
            <i class="fas fa-thumbs-up" style="color: white;margin-right: 1px;"></i>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('.circle').circleProgress({
        size: 75
    });
</script>

