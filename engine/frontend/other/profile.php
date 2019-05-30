<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="card">
            <div class="card-body p-5">
                <div class="row mb-5">
                    <div class="col-6">
                        <img class="rounded-circle" id="avatar" src="/<?= getAvatarLink($id) ?>" alt="avatar">
                    </div>
                    <div class="col text-center align-center">
                        <h4 class="mb-3"><?= $userData['name'] ?> <?= $userData['surname'] ?></h4>
                        <h5>About</h5>
                        <?php if (!is_null($userData['about']) && $userData['about'] != ''): ?>
                            <p class="text-justify"><?= nl2br($userData['about']) ?></p>
                        <?php else: ?>
                            <p class="text-justify">Nothing yet</p>
                        <?php endif; ?>
                    </div>
                    <div class="float-right">
                        <a href="/settings/"><i id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-secondary"><i class="fas fa-phone mr-3"></i> <?= $userData['phone'] ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-secondary"><i class="fas fa-envelope mr-3"></i> <?= $userData['email'] ?></p>
                    </div>
                </div>
                <?php if (!is_null($socialNetworks) && count($socialNetworks)): ?>
                <div class="row">
                    <div class="col">
                        <div class="socials text-reg">
                            <span>
                                <?php if (key_exists('vk', $socialNetworks)): ?>
                                <a href="https://vk.com/<?= $socialNetworks['vk'] ?>" target="_blank"><i class="fab fa-vk icon-social mr-3"></i></a>
                                <?php endif; ?>
                                <?php if (key_exists('facebook', $socialNetworks)): ?>
                                <a href="https://facebook.com/<?= $socialNetworks['facebook'] ?>" target="_blank"><i class="fab fa-facebook-f icon-social mr-3"></i></a>
                                <?php endif; ?>
                                <?php if (key_exists('instagram', $socialNetworks)): ?>
                                <a href="https://instagram.com/<?= $socialNetworks['instagram'] ?>" target="_blank"><i class="fab fa-instagram icon-social mr-3"></i></a>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

