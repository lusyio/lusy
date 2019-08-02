<div class="top-sidebar pt-2 pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a class="navbar-brand visible-lg" href="/"><?= $namec ?></a>
                <button class="navbar-toggler float-right position-relative" type="button" data-toggle="collapse"
                        data-target=".navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="fas fa-bars"></i>
                    <span class="events-indicator">
                    <i class="fas fa-circle mr-1 ml-1 top-sidebar-indicator d-none"></i>
                </span>
                </button>
            </div>
            <div class="col-sm-5 d-none d-md-block">
                <h2 style=" color: #27406a; font-size: 26px; font-weight: 700; "><?= $title ?></h2>
            </div>
            <div class="col-sm-4 navbarNav collapse navbar-collapse">
                <div class="position-relative searchDiv">
                    <div id="searchBtn" class="d-none">
                        <div data-toggle="tooltip" data-placement="bottom" title="Поиск"
                             class="topsidebar-noty">
                            <div class="position-relative">
                        <img class="svg-icon" src="/assets/svg/search.svg">
                            </div>
                        </div>
                    </div>
                    <form method="get" id="searchForm" action="/../search/">
                        <div class="form-group mb-0 mt-1">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input class="form-control" id="search" type="text" name="request" autocomplete="off"
                                           placeholder="<?= _('Search on tasks, comments and files') ?>...">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div id="counters" class="alerts text-center">
                    <div class="d-flex counters-topsidebar">
                        <a href="/log/#tasks" class="text-decoration-none d-none topsidebar-noty-content">
                            <div data-toggle="tooltip" data-placement="bottom" title="События"
                                 class="topsidebar-noty">
                                <div class="position-relative">
                                    <img class="svg-icon" src="/assets/svg/alarm.svg">
                                    <span class="badge badge-primary badge-topsidebar" id="notificationBadge">
                                    <small class="text-white" id="notificationCount"></small>
                                    </span>
                                </div>
                            </div>
                        </a>
                        <a href="/tasks/#overdue" class="text-decoration-none d-none topsidebar-noty-content">
                            <div data-toggle="tooltip" data-placement="bottom" title="Просроченные"
                                 class="topsidebar-noty">
                                <div class="position-relative">
                                    <img class="svg-icon" src="/assets/svg/fire.svg">
                                    <span class="badge badge-danger badge-topsidebar" id="overdueBadge">
                                    <small class="text-white" id="overdueCount"></small>
                                </span>
                                </div>
                            </div>
                        </a>
                        <a href="/log/#comments"
                           class="text-decoration-none d-none topsidebar-noty-content">
                            <div data-toggle="tooltip" data-placement="bottom" title="Комментарии"
                                 class="topsidebar-noty">
                                <div class="position-relative">
                                    <img class="svg-icon" src="/assets/svg/feedback.svg">
                                    <span class="badge badge-warning badge-topsidebar" id="commentBadge">
                                    <small class="text-white" id="commentCount"></small>
                                </span>
                                </div>
                            </div>
                        </a>
                        <a href="/mail/" class="text-decoration-none d-none topsidebar-noty-content">
                            <div data-toggle="tooltip" data-placement="bottom" title="Сообщения"
                                 class="topsidebar-noty">
                                <div class="position-relative">
                                    <img class="svg-icon" src="/assets/svg/paper-plane.svg">
                                    <span class="badge badge-success badge-topsidebar" id="messagesBadge">
                                    <small class="text-white" id="messagesCount"></small>
                                </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-1 ml-3 new-menu-trigger" data-trigger="dropdown">
                        <span>
                            <img class="user-img" src="/<?= getAvatarLink($id) ?>"/>
                        </span>
                    </div>
                    <div class="profile-submenu submenu">
                        <a href="/company/" class="p-0">
                            <div class="p-3 pt-1 pb-1">
                                <p class="navbar-brand text-uppercase font-weight-bold mb-0 text-dark"><?= $namec ?></p>
                                <br><small class="text-secondary"><?= $tariffName ?></small>
                            </div>
                        </a>
                        <?php if ($isCeo): ?>
                            <hr class="mt-0 mb-0">
                            <a href="/payment/">
                                <img class="svg-icon mr-3" src="/assets/svg/credit-card.svg"><?= _('Payment') ?>
                            </a>
                        <?php endif; ?>
                        <hr class="mt-0 mb-0">
                        <a href="/profile/<?= $id ?>/"><img class="svg-icon mr-3"
                                                            src="/assets/svg/user.svg"><?= _('Profile') ?></a>
                        <a href="/log/"><img class="svg-icon mr-3" src="/assets/svg/book.svg"><?= _('History') ?></a>
                        <a href="/settings/"><img class="svg-icon mr-3"
                                                  src="/assets/svg/controls.svg"><?= _('Settings') ?></a>
                        <a href="/logout/"><img class="svg-icon mr-3" src="/assets/svg/logout.svg"><?= _('Log Out') ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(document).ready(function () {
        var i = 0;
        $('[data-trigger="dropdown"]').on('click', function () {
            var submenu = $(this).parent().find('.submenu');
            submenu.fadeToggle(300);
            i++;
            i % 2 === 0 ? $('.new-menu-trigger').css('background-color', 'white') : $('.new-menu-trigger').css('background-color', 'rgba(95, 99, 104, 0.1)');
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest(".new-menu-trigger").length) {
                $('.submenu').fadeOut(300);
                $('.new-menu-trigger').css('background-color', 'white');
                i = 0;
                return i;
            }
            e.stopPropagation();
        });
    });
</script>