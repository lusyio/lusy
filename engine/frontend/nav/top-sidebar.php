<div class="top-sidebar pt-2 pb-2">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <a class="navbar-brand text-uppercase font-weight-bold visible-lg mt-1" href="/"><?= $namec ?></a>
                <button class="navbar-toggler float-right position-relative" type="button" data-toggle="collapse" data-target=".navbarNav"
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
                                       placeholder="<?= $GLOBALS['_searchtopsidebar'] ?>...">
                            </div>
                            <div class="input-group-append">
                                <button class="input-group-text" id="searchButton"><i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4 navbarNav collapse navbar-collapse text-right">
                <div id="counters" class="alerts position-relative">
                    <a href="/log/#tasks" class="mr-3 text-decoration-none  d-none">
                        <i class="far fa-bell" id="notificationIcon"></i>
                        <strong class="text-primary" id="notificationCount"></strong>
                    </a>
                    <a href="/tasks/#overdue" class="mr-3 text-decoration-none  d-none">
                        <i class="fas fa-fire-alt" id="overdueIcon"></i>
                        <strong class="text-danger" id="overdueCount"></strong>
                    </a>
                    <a href="/log/#comments" class="mr-3 text-decoration-none  d-none">
                        <i class="far fa-comment" id="commentIcon"></i>
                        <strong class="text-warning" id="commentCount"></strong>
                    </a>
                    <a href="/mail/" class="mr-3 text-decoration-none  d-none">
                        <i class="far fa-envelope" id="messagesIcon"></i>
                        <strong class="text-success" id="messagesCount"></strong>
                    </a>
                    <? //=$id?><!--/"><img class="user-img rounded-circle" src="/upload/avatar/-->
                    <? //=$id?><!--.jpg"/></a>-->
                    <span class="profile-menu-trigger" data-trigger="dropdown">
                        <img class="user-img rounded-circle " src="/<?= getAvatarLink($id) ?>"/>
                    </span>
                    <div class="profile-submenu submenu">
                        <a href="/profile/<?= $id ?>/"><i class="mr-2 fas fa-user-alt"></i><?= $_profile ?></a>
                        <a href="/log/"><i class="mr-2 fas fa-bell"></i><?= $_history ?></a>
                        <a href="/settings/"><i class="mr-2 fas fa-cog"></i><?= $_settings ?></a>
                        <a href="/logout/"><i class="mr-2 fas fa-sign-out-alt"></i><?= $_logout ?></a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('[data-trigger="dropdown"]').on('click', function () {
            var submenu = $(this).parent().find('.submenu');
            submenu.fadeToggle(300);

        });
        $(document).on('click', function (e) {
            if (!$(e.target).closest(".profile-menu-trigger").length) {
                $('.submenu').fadeOut(300);
            }
            e.stopPropagation();
        });
    });
</script>