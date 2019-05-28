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
                    <span class="badge <?= $statusBar[$status]['bg'] ?>"><?= $GLOBALS["_$status"] ?></span>
                </div>
                <div class="col-8">
                    <div class="float-right" title="<?= $GLOBALS["_$status"] ?>">
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$status]['bg1'] ?>"><i
                                    class="<?= $statusBar[$status]['ic1'] ?> custom"></i></span>
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$status]['bg2'] ?>"><i
                                    class="<?= $statusBar[$status]['ic2'] ?> custom"></i></span>
                        <span class="status-icon-last rounded-circle noty-m <?= $statusBar[$status]['bg3'] ?>"><i
                                    class="<?= $statusBar[$status]['ic3'] ?> custom"></i></span>
                    </div>
                </div>
            </div>
            <h4 class="<?= $statusBar[$status]['border'] ?> font-weight-bold mb-3 mt-5"><?= $nametask ?></h4>
            <hr>
            <div class="row">
                <div class="col-5">
                    <div class="position-relative deadline-block">
                        <div class="progress position-relative mr-1"
                             style="height: 30px; font-size: 14px; z-index: 1; ">
                            <div class="progress-bar bg-secondary-custom rounded" role="progressbar" style="width: 5%"
                                 aria-valuenow="5%" aria-valuemin="0" aria-valuemax="100"></div>
                            <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                                <div class="p-1 date-inside">
                                    <i class="far fa-calendar-times text-ligther-custom"></i><span
                                            class="text-ligther-custom ml-2"><?= $GLOBALS['_deadlinelist'] ?></span> <?= $dayDone ?> <?= $monthDone ?>
                                    <span></span>
                                </div>
                            </medium>
                        </div>
                        <span class="position-absolute edit"><i class="fas fa-pencil-alt"></i></span>
                    </div>

                </div>
                <div class="col-7">
                    <div class="float-right">
                        <img src="/upload/avatar/<?= $manager ?>.jpg" class="avatar mr-1">
                        <span class=" text-secondary slash">|</span>
                        <?php
                        foreach ($coworkers as $coworker):
                            if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                                $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
                            } else {
                                $viewStatusTitle = 'Не просмотрено';
                            }
                            ?>
                            <span class="mb-0" title="<?= $viewStatusTitle ?>"><img
                                        src="/upload/avatar/<?= $coworker['worker_id'] ?>.jpg" alt="worker image"
                                        class="avatar ml-1"></span>
                        <?php endforeach; ?>
                        <div class="tooltip-avatar">
                            <i class="far fa-plus-square avatar-new"></i>
                            <?php
                            include 'engine/frontend/members/members.php';
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="change-date">
                <div class="form-group mb-0 p-2">
                    <div class="row">
                        <div class="col">
                            <?php if ($role != 'manager'): ?>
                                <textarea name="report" id="reportarea1" class="form-control" rows="4"
                                          placeholder="Причина" required></textarea>
                            <?php endif; ?>
                            <input class="form-control form-control-sm" value="" type="date" id="example-date-input"
                                   min="">
                            <button type="submit" id="<?= ($role == 'manager') ? 'sendDate' : 'sendpostpone'; ?>"
                                    class="btn btn-success btn-sm text-center mt-1 mb-1"><?= $GLOBALS["_change"] ?></button>
                        </div>
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


    <div class="card mt-3">
        <div class="card-body">
            <div class="d-flex comin">
                <input class="form-control mr-3" id="comin" name="comment" type="text" autocomplete="off"
                       placeholder="<?= $GLOBALS["_writecomment"] ?>..." required>

                <button type="submit" class="btn btn-light btn-file mr-3"><i class="fas fa-file-upload custom-date"></i><input
                            id="sendFiless" type="file"></button>

                <button type="submit" id="comment" class="btn btn-primary" title="<?= $GLOBALS['_send'] ?>"><i
                            class="fas fa-paper-plane"></i></button>
            </div>
        </div>
    </div>

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
    $(document).ready(function () {

        // $(document).on('click', function (e) {
        //     if (!$(e.target).closest(".deadline-block").length) {
        //         $('#change-date').fadeOut(300);
        //     }
        // if (!$(e.target).closest(".tooltip-avatar").length) {
        //     $('.members').fadeOut(300);
        //     $('.coworkers').fadeOut(300);
        //     $('.responsible').fadeOut(300);
        // }
        // e.stopPropagation();
        // });

        $(".deadline-block").on('click', function () {
            $("#change-date").fadeToggle(300);
        });

        $(".avatar-new").on('click', function (e) {
            $(".members").fadeToggle(300);
        });


        $(".changeResponsible").on('click', function () {
            var selectedName = $(this).parent().siblings('.col').text();
            console.log(selectedName);
            var selectedId = $(this).attr('value');
            // var responsible = $('.responsible-one[value = ' + selectedId + ']');

            $(".members-responsible-one").html("<div class=\"responsible-one text-justify\">\n" +
                "                        <div class=\"row\">\n" +
                "                            <div class=\"col-1\">\n" +
                "                                <img attr=" + selectedId + " src=\"/upload/avatar/" + selectedId + ".jpg\" class=\"avatar-added mr-1\">\n" +
                "                            </div>\n" +
                "                            <div class=\"col\">\n" +
                "                                <a href=\"#\" class=\"mb-1 add-coworker-text\">" + selectedName + "</a>\n" +
                "                            </div>\n" +
                "                            <div class=\"col-2\">\n" +
                "                            </div>\n" +
                "                        </div>\n" +
                "                    </div>");

            // console.log(selectedId);
            // console.log(responsible);
        });

        var coworkersId = new Map();
        var selectedId;
        $(".addNewWorker").on('click', function () {
            var selectedName = $(this).parent().siblings('.col').text();
            selectedId = $(this).attr('value');
            coworkersId.set(selectedId, selectedId);
            console.log(coworkersId);
            $(this).closest('.coworkersList-coworker').addClass('bg-coworker');
            $(".container-coworker").append("<div class=\"add-worker mr-1 mb-1\">\n" +
                "                        <img src=\"/upload/avatar/" + selectedId + ".jpg\"\n" +
                "                             class=\"avatar-added mr-1\">\n" +
                "                        <a href=\"#\" class=\"card-coworker\">" + selectedName + "</a>\n" +
                "                        <span><i value=\'" + selectedId + "\'\n" +
                "                                 class=\"deleteWorker fas fa-times cancel card-coworker-delete\"></i></span>\n" +
                "                    </div>");

            $(".tooltip-avatar").prepend("<span class=\"mb-0\"><img src=\"/upload/avatar/" + selectedId + ".jpg\" alt=\"worker image\" class=\"avatar mr-1 ml-1\"></span>");
        });

        $(".tooltip-avatar").on('click', '.deleteWorker', function () {
            $(this).closest('.add-worker').remove();
            var removedId = $(this).attr('value');
            coworkersId.delete(removedId);
            console.log(coworkersId);
            var numb = $('.addNewWorker[value = ' + removedId + ']');
            numb.parents('.coworkersList-coworker').removeClass('bg-coworker');
        });

        $("#confirmMembers").on('click', function () {
            var responsibleId = $(".members-responsible-one ").find('img').attr('attr');
            var fd = new FormData();
            coworkersId.forEach(function (value, i) {
                fd.append('coworkerId' + i, value);
            });
            fd.append('responsibleId', responsibleId);
            $.post("/ajax.php", {
                module: 'addCoworker',
                it: $it,
                ajax: 'task-control'
            }, controlUpdate);

            function controlUpdate(data) {
                console.log(coworkersId);
                console.log(responsibleId);
            }
            $(".members").fadeOut(300);
        });

    });
</script>
