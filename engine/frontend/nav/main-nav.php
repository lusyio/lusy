<?php
if ($companyUsageSpacePercent > 90) {
    $bguser = 'bg-danger';
    $bgall = 'bg-danger';

} else {
    $bguser = 'bg-dark';
    $bgall = 'bg-primary';
}
?>
<nav class="navbar-expand-lg flex-column">

    <div class="collapse navbar-collapse navbarNav" id="navbarNav">
        <ul class="navbar-nav w-100">
            <?php if (in_array('main', $menu[$roleu])): ?>
                <li class="nav-item active pb-2"><a class="nav-link" href="/">
                        <img class="svg-icon mr-3" src="/assets/svg/home.svg">
                        <?= _('Dashboard') ?></a></li>
            <?php endif; ?>
            <?php if (in_array('tasks', $menu[$roleu])): ?>
                <li class="nav-item pb-2">
                    <a class="nav-link" href="/tasks/">
                        <img class="svg-icon mr-3" src="/assets/svg/check-list.svg">
                        <?= _('Tasks') ?>
                        <div class="float-right">
                            <span class="badge badge-primary float-left"
                                  style="border-top-right-radius: 0px; border-bottom-right-radius: 0px;"
                                  data-toggle="tooltip" data-placement="bottom"
                                  title="<?= $GLOBALS['_outbox'] ?>"><?= $manager ?></span>
                            <span class="badge badge-dark float-right"
                                  style=" border-top-left-radius: 0px; border-bottom-left-radius: 0px; "
                                  data-toggle="tooltip" data-placement="bottom"
                                  title="<?= $GLOBALS['_inbox'] ?>"><?= $worker ?></span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (in_array('newTask', $menu[$roleu])): ?>
                <li class="nav-item"><a class="nav-link" href="/task/new/">
                        <img class="svg-icon mr-3" src="/assets/svg/add-button.svg">
                        <?= _('Create task') ?></a></li>
                <hr>
            <?php endif; ?>
            <?php if (in_array('company', $menu[$roleu])): ?>
                <li class="nav-item pb-2"><a class="nav-link" href="/company/">
                        <img class="svg-icon mr-3" src="/assets/svg/users.svg">
                        <?= _('Company') ?></a></li>
            <?php endif; ?>
            <li class="nav-item pb-2"><a class="nav-link" href="/awards/">
                    <img class="svg-icon mr-3" src="/assets/svg/cup.svg">
                    <?= _('Awards') ?></a></li>
            <?php if (in_array('reports', $menu[$roleu])): ?>
                <li class="nav-item pb-2">
                    <a class="nav-link" href="/reports/">
                        <img class="svg-icon mr-3" src="/assets/svg/pie-chart.svg">
                        <?= $GLOBALS['_reports'] ?></a></li>
            <?php endif; ?>
            <?php if (in_array('storage', $menu[$roleu])): ?>
                <li class="nav-item pb-2 files-nav">
                    <a class="nav-link" href="/storage/">
                        <img class="svg-icon mr-3" src="/assets/svg/upload.svg">
                        <?= _('Storage') ?>
                        <div class="progress mt-2">
                            <div class="progress-bar <?= $bguser ?>" role="progressbar"
                                 style="width: <?= $userUsageSpacePercent ?>%"
                                 aria-valuenow="<?= $userUsageSpacePercent ?>" aria-valuemin="0" aria-valuemax="100"
                                 title="<?= $GLOBALS["_titleuserusage"] ?>"></div>
                            <div class="progress-bar <?= $bgall ?>" role="progressbar"
                                 style="width: <?= $companyUsageSpacePercent - $userUsageSpacePercent ?>%"
                                 aria-valuenow="<?= $companyUsageSpacePercent - $userUsageSpacePercent ?>"
                                 aria-valuemin="0" aria-valuemax="100"
                                 title="<?= $GLOBALS["_titlecompanyusage"] ?>"></div>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($idc == 1) : ?>
                <hr>
                <li class="nav-item pb-2"><a class="nav-link" href="/godmode/">
                        <img class="svg-icon mr-3" src="/assets/svg/settings.svg">
                        <?= _('GodMode') ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<script>
    var pathname = window.location.pathname;
    if (pathname.indexOf('tasks') > -1 || pathname.indexOf('task') > -1) {
        $('.nav-item').removeClass('active').has('[href="/tasks/"]').addClass('active');
    }
    if (pathname.indexOf('new') > -1) {
        $('.nav-item').removeClass('active').has('[href="/task/new/"]').addClass('active');
    }
    if (pathname.indexOf('company') > -1 || pathname.indexOf('profile') > -1 || pathname.indexOf('payment') > -1 || pathname.indexOf('settings') > -1 || pathname.indexOf('mail') > -1 || pathname.indexOf('chat') > -1 || pathname.indexOf('company-settings') > -1 || pathname.indexOf('invite') > -1) {
        $('.nav-item').removeClass('active').has('[href="/company/"]').addClass('active');
    }
    if (pathname.indexOf('awards') > -1) {
        $('.nav-item').removeClass('active').has('[href="/awards/"]').addClass('active');
    }
    if (pathname.indexOf('storage') > -1) {
        $('.nav-item').removeClass('active').has('[href="/storage/"]').addClass('active');
    }
    if (pathname.indexOf('reports') > -1) {
        $('.nav-item').removeClass('active').has('[href="/reports/"]').addClass('active');
    }
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>