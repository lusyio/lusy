<div class="members">
    <div class="members-card position-relative">
        <div class="text-justify owner">
            <img title="<?= $viewStatusTitle ?>" src="/<?= getAvatarLink($manager) ?>"
                 class="avatar-added mr-1">
            <a href="#"><?= $task['managerName'] ?> <?= $task['managerSurname'] ?></a>
        </div>
        <hr class="m-0">
        <div class="members-responsible">
            <div class="row" style="padding: 5px;">
                <div class="col text-left">
                    <span>Ответственный</span>
                </div>
                <div class="col-2 text-right">
                    <i class="fas fa-pencil-alt icon-members-change-responsible" data-toggle="collapse"
                       data-target="#responsibleList" aria-expanded="false" aria-controls="responsibleList"></i>
                </div>
            </div>
            <hr class="mt-0 mb-1">
            <div val="" class="row members-responsible-selected">
                <div class="col-1">
                    <img title="<?= $viewStatusTitle ?>" src="/<?= getAvatarLink($worker) ?>"
                         class="avatar-added mr-1">
                </div>
                <div class="col text-left">
                    <span><?= $task['workerName'] ?> <?= $task['workerSurname'] ?></span>
                </div>
            </div>
            <hr class="mt-1 mb-1">
            <div class="text-left collapse" id="responsibleList">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>" class="row members-select-responsible">
                        <div class="col-1">
                            <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added">
                        </div>
                        <div class="col">
                            <span class="add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                        </div>
                        <div class="col-2 text-right">
                            <i class="fas fa-exchange-alt icon-change-responsible"></i>
                        </div>
                    </div>
                <?php } ?>
                <hr class="mt-1 mb-0">
            </div>
        </div>
        <div class="members-coworkers">
            <div class="row" style="padding: 5px;">
                <div class="col text-justify">
                    <span>Соисполнители</span>
                </div>
                <div class="col-2 text-right">
                    <i class="fas fa-pencil-alt icon-members-change-coworker" data-toggle="collapse"
                       data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList"></i>
                </div>
            </div>
            <div class="mb-1 container p-1 container-coworker d-flex flex-wrap align-content-sm-stretch">
                <?php
                foreach ($coworkers as $coworker):
                    if (!is_null($viewStatus) && isset($viewStatus[$coworker['worker_id']])) {
                        $viewStatusTitle = 'Просмотрено ' . $viewStatus[$coworker['worker_id']]['datetime'];
                    } else {
                        $viewStatusTitle = 'Не просмотрено';
                    }
                    ?>
                    <div val="<?= $coworker['worker_id'] ?>" class="add-worker">
                        <img title="<?= $viewStatusTitle ?>" src="/<?= getAvatarLink($coworker['worker_id']) ?>"
                             class="avatar-added mr-1">
                        <span class="coworker-fio"><?= $coworker['name'] ?> <?= $coworker['surname'] ?></span>
                        <i class="fas fa-times icon-newtask-delete-coworker"></i>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="text-left collapse" id="coworkersList">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>" class="row members-coworker-select">
                        <div class="col-1">
                            <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added">
                        </div>
                        <div class="col">
                            <span class="add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                        </div>
                        <div class="col-2 text-right">
                            <i class="fas fa-plus icon-add-coworker"></i>
                        </div>
                    </div>
                <?php } ?>
                <hr class="mt-1 mb-1">
            </div>
            <div class="mt-3 text-center">
                <button class="btn btn-success btn-sm" id="confirmMembers" type="button">Принять</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(".avatar-new").on('click', function (e) {
            $(".members").fadeToggle(300);
        });

        //работа с ответственными
        $(".members-select-responsible").on('click', function () {
            var id = $(this).attr('val');
            var selected = $('.members-responsible-selected:visible').attr('val');
            $('#responsibleList').find("[val = " + selected + "]").removeClass('d-none');
            console.log(selected);
            $(this).addClass('d-none');
            $('.add-responsible').addClass('d-none');
            $('#coworkersList').find("[val = " + id + "]").addClass('d-none');
            $('.container-coworker').find("[val = " + id + "]").addClass('d-none');
            $('.container-responsible').find("[val = " + id + "]").removeClass('d-none');
            updateCoworkers();
        });

        //работа с соисполнителями
        $('.add-worker').on('click', function () {
            var id = $(this).attr('val');
            console.log(id);
            $(this).addClass('d-none');
            $('.coworker-card').find("[val = " + id + "]").removeClass('d-none');
            updateResponsible()
        });

        $(".select-coworker").on('click', function () {
            var id = $(this).attr('val');
            console.log(id);
            $(this).addClass('d-none');
            $('.responsible-card').find("[val = " + id + "]").addClass('d-none');
            $('.container-coworker').find("[val = " + id + "]").removeClass('d-none');
            updateResponsible()
        });

        $(".tooltip-avatar").on('click', '.deleteWorker', function () {
            $(this).closest('.add-worker').remove();
            var removedId = $(this).attr('value');
            coworkersId.delete(removedId);
            console.log(coworkersId);
            var numb = $('.addNewWorker[value = ' + removedId + ']');
            numb.parents('.coworkersList-coworker').removeClass('bg-coworker');
        });

        // $("#confirmMembers").on('click', function () {
        //     var fd = new FormData();
        //     coworkersId.forEach(function (value, i) {
        //         fd.append('coworkerId' + i, value);
        //     });
        //     $.post("/ajax.php", {
        //         module: 'addCoworker',
        //         it: $it,
        //         ajax: 'task-control'
        //     }, controlUpdate);
        //
        //     function controlUpdate(data) {
        //         console.log(coworkersId);
        //     }
        //     $(".members").fadeOut(300);
        // });

    });
</script>
