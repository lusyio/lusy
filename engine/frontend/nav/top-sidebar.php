<div class="top-sidebar pt-2 pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a class="navbar-brand text-uppercase font-weight-bold visible-lg mt-1" href="/"><?= $namec ?></a>
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
                <form method="get" id="searchForm" action="/../search/">
                    <div class="form-group mb-0 mt-1">
                        <div class="input-group">
                            <div class="custom-file">
                                <input class="form-control" id="search" type="text" name="request" autocomplete="off"
                                       placeholder="<?= _('Search on tasks, comments and files') ?>...">
                            </div>
                            <div class="input-group-append">
                                <button class="input-group-text" id="searchButton"><i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4 navbarNav collapse navbar-collapse">
                <div id="counters" class="alerts text-center">
                    <div class="d-flex counters-topsidebar">
                        <div data-toggle="tooltip" data-placement="bottom" title="Новые задачи" class="topsidebar-noty">
                            <div class="position-relative">
                                <a href="/log/#tasks" class="text-decoration-none d-none topsidebar-noty-content">
                                    <i class="far fa-bell" id="notificationIcon" style="font-size: 18px"></i>
                                    <span class="badge badge-primary badge-topsidebar" id="notificationBadge"">
                                    <small class="text-white" id="notificationCount"></small>
                                    </span>
                                </a>
                            </div>
                        </div>
                        <div data-toggle="tooltip" data-placement="bottom" title="Просроченные" class="topsidebar-noty">
                            <div class="position-relative">
                                <a href="/tasks/#overdue" class="text-decoration-none d-none topsidebar-noty-content">
                                    <i class="fas fa-fire-alt" id="overdueIcon" style="font-size: 18px"></i>
                                    <span class="badge badge-danger badge-topsidebar" id="overdueBadge">
                                    <small class="text-white" id="overdueCount"></small>
                                </span>
                                </a>
                            </div>
                        </div>
                        <div data-toggle="tooltip" data-placement="bottom" title="Новые комментарии"
                             class="topsidebar-noty">
                            <div class="position-relative">
                                <a href="/log/#comments"
                                   class="text-decoration-none d-none topsidebar-noty-content">
                                    <i class="far fa-comment" id="commentIcon" style="font-size: 18px"></i>
                                    <span class="badge badge-warning badge-topsidebar" id="commentBadge">
                                    <small class="text-white" id="commentCount"></small>
                                </span>
                                </a>
                            </div>
                        </div>
                        <div data-toggle="tooltip" data-placement="bottom" title="Новые сообщения"
                             class="topsidebar-noty">
                            <div class="position-relative">
                                <a href="/mail/" class="text-decoration-none d-none topsidebar-noty-content">
                                    <i class="far fa-envelope" id="messagesIcon" style="font-size: 18px"></i>
                                    <span class="badge badge-success badge-topsidebar" id="messagesBadge">
                                    <small class="text-white" id="messagesCount"></small>
                                </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="p-1 ml-3 new-menu-trigger" data-trigger="dropdown" style="border-radius: 50%;">
                        <span>
                            <img class="user-img rounded-circle " src="/<?= getAvatarLink($id) ?>"/>
                        </span>
                    </div>
                    <div class="profile-submenu submenu">
                        <a href="/profile/<?= $id ?>/"><i class="mr-2 fas fa-user-alt"></i><?= _('Profile') ?></a>
                        <a href="/log/"><i class="mr-2 fas fa-bell"></i><?= _('History') ?></a>
                        <a href="/settings/"><i class="mr-2 fas fa-cog"></i><?= _('Settings') ?></a>
                        <a href="/logout/"><i class="mr-2 fas fa-sign-out-alt"></i><?= _('Log Out') ?></a>
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
            submenu.toggle(300);
            i++;
            console.log(i);
            i % 2 === 0 ? $('.new-menu-trigger').css('background-color', 'white') : $('.new-menu-trigger').css('background-color', 'rgba(95, 99, 104, 0.24)');
        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest(".new-menu-trigger").length) {
                $('.submenu').hide(300);
                $('.new-menu-trigger').css('background-color', 'white');
                i = 0;
                return i;
            }
            e.stopPropagation();
        });
    });
</script>