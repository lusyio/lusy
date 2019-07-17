<?php
$badges = [
    'meeting' => 'fas fa-handshake',
    'invitor' => 'fas fa-user-plus',
    'bugReport_1' => 'fab fa-accessible-icon',
    'message_1' => 'fas fa-broadcast-tower',
    'taskOverdue_1' => 'fas fa-meh',
    'taskPostpone_1' => 'fas fa-crown',
    'taskDoneWithCoworker_1' => 'fas fa-user-friends',
    'selfTask_1' => 'fas fa-user-tie',
    'taskDone_1' => 'fas fa-thumbs-up',
    'taskDone_10' => 'fas fa-star',
    'taskDone_50' => 'fas fa-star',
    'taskDone_100' => 'fas fa-star',
    'taskDone_200' => 'fas fa-star',
    'taskDone_500' => 'fas fa-star',
    'taskDonePerMonth_500' => 'fas fa-user-graduate',
    'taskCreate_10' => 'fas fa-atom',
    'taskCreate_50' => 'fas fa-atom',
    'taskCreate_100' => 'fas fa-atom',
    'taskCreate_200' => 'fas fa-atom',
    'taskCreate_500' => 'fas fa-atom',
    'comment_1000' => 'fas fa-comment',
    'taskOverduePerMonth_0' => 'fas fa-medal',
    'taskDonePerMonth_leader' => 'fas fa-greater-than',
    'taskInwork_20' => 'fas fa-brain',
    'taskCreatePerDay_30' => 'fas fa-bolt',
];

$month = ['', _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December")];
$monthNumber = date("n", strtotime($userData['birthdate']));
?>
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
                    <?php
                    if ($userData['name'] != null && $userData['surname'] != null):
                        ?>
                        <div class="fio-profile"><?= $userData['name'] ?> <?= $userData['surname'] ?></div>
                    <?php else: ?>
                        <div class="fio-profile"><?= $userData['email'] ?></div>
                    <?php
                    endif;
                    ?>
                    <span class="text-muted-reg"><?= ($userData['online']) ? $GLOBALS['_online'] : ((isset($userData['activity'])) ? $GLOBALS['_wasOnline'] . ' ' . date('d.m H:i', $userData['activity']) : '') ?></span>
                    <?php if ($id === $profileId): ?>
                        <div class="icon-edit-profile">
                            <a data-toggle="tooltip" data-placement="bottom" title="Изменить профиль" href="/settings/"><i
                                        id="editProfile" class="fas fa-pencil-alt edit-profile"></i></a>
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
                    <div class="row mt-25-tasknew">
                        <div class="col-12 text-center position-relative">
                            <div class="other-func text-center position-relative" data-toggle="collapse" href="#collapseFunctions" role="button" aria-expanded="false" aria-controls="collapseFunctions">
                                <div class="additional-func">
                                    <span style="background-color: #fff">Показать всю информацию <i class="fas fa-caret-down"></i></span>
                                </div>
                            </div>
                            <div class="collapse" id="collapseFunctions">
                                <div class="row">
                                    <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                                        <p class="mb-1 text-justify"><span class="text-secondary">Дата рождения:</span> <?= date('d', strtotime($userData['birthdate'])); ?> <?= _($month[$monthNumber]) ?>, <?= date('Y', strtotime($userData['birthdate'])); ?></p>
                                        <p class="mb-0 text-justify"><span class="text-secondary">О себе:</span> <?= nl2br($userData['about']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3 d-none achievements-container">
    <div class="col-12 col-lg-10 col-md-10 col-xl-10">
        <h4 class="ml-2">Достижения</h4>
        <div class="d-flex flex-wrap text-center">
            <?php
            foreach ($achievementProfile as $name => $values):?>
                <?php if ($values['got']): ?>
                    <div class="award award-sm mt-3" data-toggle="tooltip" data-placement="bottom"
                         title="<?= $GLOBALS['_' . $name . '_text'] ?>">
                        <div>
                            <div class="circle" data-fill="{ &quot;color&quot;: &quot;rgba(0, 123, 255, 1)&quot;}"
                                 data-value="1.00"></div>
                            <div class="award-star bg-primary">
                                <i class="fas <?= $badges[$name] ?>"></i>
                            </div>
                        </div>
                        <h6 class="text-uppercase font-weight-bold"><?= $GLOBALS['_' . $name] ?></h6>
                        <hr>
                        <span class="badge badge-primary"><?= date('d.m.Y', $values['datetime']) ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <span class="position-absolute all-achieve">
            <span class="small text-muted text-all-achieve">Посмотреть все</span>
        </span>
    </div>
</div>

<script>
    $(document).ready(function () {
        if ($('.award-sm').length != 0) {
            $('.achievements-container').removeClass('d-none');
        }
        if ($('.award-sm').length > 3) {
            $('.all-achieve').show();
        }
        $('.award-sm:lt(3)').show();
        $('.text-all-achieve').on('click', function () {
            if ($(this).hasClass('active')) {
                $('.award-sm:not(:lt(3))').fadeOut(200);
                console.log('asdasd');
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
                $('.award-sm').show();
            }
        })
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('.circle').circleProgress({
        size: 75
    });
</script>

