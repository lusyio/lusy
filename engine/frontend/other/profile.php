<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="card">
            <div class="card-body p-4">
                <div class="row mb-4">
                    <div class="col-3 col-lg-5 text-center">
                        <img class="rounded-circle avatar-profile" id="avatar" src="/<?= getAvatarLink($profileId) ?>"
                             alt="avatar">
                    </div>
                    <div class="col">
                        <h4><?= $userData['name'] ?> <?= $userData['surname'] ?></h4>
                        <span class="text-muted-reg"><?= ($userData['online']) ? $GLOBALS['_online'] : ((isset($userData['activity'])) ? $GLOBALS['_wasOnline'] . ' ' . date('d.m H:i', $userData['activity']) : '') ?></span>
                        <p class="text-secondary mt-4 mb-2">
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
                    <?php if ($id === $profileId): ?>
                        <div class="float-right">
                            <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                        </div>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="text-reg"> О себе:</div>
                <?php if (!is_null($userData['about']) && $userData['about'] != ''): ?>
                    <p class="text-justify text-reg"><?= nl2br($userData['about']) ?></p>
                <?php else: ?>
                    <p class="text-justify text-reg"><?= $GLOBALS['_aboutprofile'] ?></p>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

