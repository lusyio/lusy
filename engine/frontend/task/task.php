<?php
$statusBar = [
    'new' => [
        'border' => 'border-success',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'inwork' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'overdue' => [
        'border' => 'border-danger',
        'bg' => 'badge-danger',
        'bg1' => 'bg-danger',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fab fa-gripfire',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'postpone' => [
        'border' => '',
        'bg' => 'badge-warning',
        'bg1' => 'bg-warning',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'far fa-clock',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'pending' => [
        'border' => 'border-warning',
        'bg' => 'badge-warning',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-warning',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'returned' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'done' => [
        'border' => 'border-success',
        'bg' => 'badge-success',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-success',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'canceled' => [
        'border' => 'border-secondary',
        'bg' => 'badge-danger',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-danger',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-times',
    ],
];
if ($dayost < 0) {
    $statusBar['postpone']['border'] = 'border-danger';
};
if ($view == 0) {
    $statusBar[$status]['border'] = 'border-secondary';
};
if ($id == $worker and $view == 0) {
    $statusBar[$status]['border'] = 'border-primary';
}
?>
<div id="task">
    <div class="card" style="margin-top: -21px;">
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <span class="badge <?= $statusBar[$task['status']]['bg'] ?>"><?= $GLOBALS["_{$task['status']}"] ?></span>
                </div>
                <div class="col-8">
                    <div class="float-right" title="<?= $GLOBALS["_$status"] ?>">
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$task['status']]['bg1'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic1'] ?> custom"></i></span>
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$task['status']]['bg2'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic2'] ?> custom"></i></span>
                        <span class="status-icon-last rounded-circle noty-m <?= $statusBar[$task['status']]['bg3'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic3'] ?> custom"></i></span>
                    </div>
                </div>
            </div>
            <h4 class="<?= $statusBar[$status]['border'] ?> font-weight-bold mb-3 mt-5"><?= $nametask ?></h4>
            <hr>
            <div class="row">
                <div class="col-6 col-lg-4">
                    <div class="position-relative deadline-block">
                        <div class="progress deadline-block-progress position-relative mr-1">
                            <div class="progress-bar bg-secondary-custom rounded" role="progressbar"
                                 style="width: <?= $dateProgress ?>%"
                                 aria-valuenow="<?= $dateProgress ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                            <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                                <div class="p-1 date-inside">
                                    <i class="far fa-calendar-times text-ligther-custom"></i><span
                                            class="text-ligther-custom ml-2 deadline-block-word"><?= $GLOBALS['_deadlinelist'] ?></span> <?= $dayDone ?> <?= $monthDone ?>
                                    <span></span>
                                </div>
                            </medium>
                        </div>
                        <span class="position-absolute edit"><i class="fas fa-pencil-alt"></i></span>
                        <div id="change-date">
                            <div class="form-group mb-0 p-3">
                                <?php if ($role != 'manager'): ?>
                                    <textarea name="report" id="reportarea1" class="form-control mb-2" rows="3"
                                              placeholder="Причина" required></textarea>
                                <?php endif; ?>
                                <input class="form-control form-control-sm mb-2" value="" type="date"
                                       id="deadlineInput"
                                       min="">
                                <button type="submit"
                                        id="<?= ($role == 'manager') ? 'sendDate' : 'sendpostpone'; ?>"
                                        class="btn btn-primary btn-sm float-left mb-3"><?= $GLOBALS["_change"] ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-8 ">
                    <div class="member-block">
                        <div class="float-right members-block">
                            <img src="/<?= getAvatarLink($manager) ?>" class="avatar mr-1">
                            <span class=" text-secondary slash">|</span>
                            <img src="/<?= getAvatarLink($worker) ?>" class="avatar ml-1">
                            <?php
                            foreach ($coworkers as $coworker):
                                if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                                    $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
                                } else {
                                    $viewStatusTitle = 'Не просмотрено';
                                }
                                ?>
                                <span class="mb-0" title="<?= $viewStatusTitle ?>"><img
                                            src="/<?= getAvatarLink($coworker['worker_id']) ?>" alt="worker image"
                                            class="avatar ml-1"></span>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($role == 'manager') {
                            include 'engine/frontend/members/members.php';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="mt-5 mb-5 text-justify"><?= $description ?></div>
            <?php if (count($files) > 0): ?>
                <?php foreach ($files as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="text-secondary"><s><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></s>
                            (удален)</p>
                    <?php else: ?>
                        <p class="text-secondary"><a class="text-secondary" href="../../<?= $file['file_path'] ?>"><i
                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <div id="control">
                <?php
                include 'engine/backend/task/task/control/' . $role . '/' . $status . '.php';
                include 'engine/frontend/task/control/' . $role . '/' . $status . '.php';
                ?>
            </div>
        </div>
    </div>
    <?php if ($enableComments): ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex comin">
                <textarea class="form-control mr-3" id="comin" rows="1" name="comment" type="text" autocomplete="off"
                          placeholder="<?= $GLOBALS["_writecomment"] ?>..." required></textarea>

                    <button data-toggle="tooltip" data-placement="bottom" title="Прикрепить файлы" type="submit"
                            class="btn btn-light btn-file mr-3"><i class="fas fa-file-upload custom-date"></i><input
                                id="sendFiles" type="file" multiple></button>

                    <button type="submit" id="comment" class="btn btn-primary" title="<?= $GLOBALS['_send'] ?>"><i
                                class="fas fa-paper-plane"></i></button>
                </div>
                <div style="display: none" class="bg-white file-name container-files">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card mt-3">
        <div class="card-body">
            <?php include 'engine/frontend/task/notyfeed.php' ?>
        </div>
    </div>
</div>
<script>
    var $it = '<?=$idtask?>';
</script>
<script src="/assets/js/task.js"></script>
<script src="/assets/js/datepicker.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    (function (b) {
        b.fn.autoResize = function (f) {
            var a = b.extend({
                onResize: function () {
                }, animate: !0, animateDuration: 150, animateCallback: function () {
                }, extraSpace: 20, limit: 1E3
            }, f);
            this.filter("textarea").each(function () {
                var d = b(this).css({"overflow-y": "hidden", display: "block"}), f = d.height(), g = function () {
                    var c = {};
                    b.each(["height", "width", "lineHeight", "textDecoration", "letterSpacing"], function (b, a) {
                        c[a] = d.css(a)
                    });
                    return d.clone().removeAttr("id").removeAttr("name").css({
                        position: "absolute",
                        top: 0,
                        left: -9999
                    }).css(c).attr("tabIndex", "-1").insertBefore(d)
                }(), h = null, e = function () {
                    g.height(0).val(b(this).val()).scrollTop(1E4);
                    var c = Math.max(g.scrollTop(), f) + a.extraSpace, e = b(this).add(g);
                    h !== c && (h = c, c >= a.limit ? b(this).css("overflow-y", "") : (a.onResize.call(this), a.animate && "block" === d.css("display") ? e.stop().animate({height: c}, a.animateDuration, a.animateCallback) : e.height(c)))
                };
                d.unbind(".dynSiz").bind("keyup.dynSiz", e).bind("keydown.dynSiz", e).bind("change.dynSiz", e)
            });
            return this
        }
    })(jQuery);

    // инициализация
    jQuery(function () {
        jQuery('textarea').autoResize();
    });

    $(document).ready(function () {
        <?= ($worker == $id && $view == '0') ? 'decreaseTaskCounter();' : '' ?>
        $(document).on('click', function (e) { // событие клика по веб-документу
            var div = $(".deadline-block"); // тут указываем ID элемента
            var dov = $('#change-date');
            if (!div.is(e.target)  // если клик был не по нашему блоку
                && div.has(e.target).length === 0) { // и не по его дочерним элементам
                dov.fadeOut(200); // скрываем его
            }
        });

        // if (!$(e.target).closest(".tooltip-avatar").length) {
        //     $('.members').fadeOut(300);
        //     $('.coworkers').fadeOut(300);
        //     $('.responsible').fadeOut(300);
        // }
        // e.stopPropagation();
        // });

        $(".deadline-block").on('click', function () {
            $("#change-date").fadeIn(200);
        });
    });
</script>
