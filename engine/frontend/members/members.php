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
            <div class="container-members-responsible-selected">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="row members-responsible-selected <?= ($n['id'] == $worker) ? '' : 'd-none' ?>">
                        <div class="col-1">
                            <img title="<?= $viewStatusTitle ?>" src="/<?= getAvatarLink($n['id']) ?>"
                                 class="avatar-added mr-1">
                        </div>
                        <div class="col text-left">
                            <span><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                        </div>
                    </div>
                <?php } ?>
                <hr class="mt-1 mb-1">
            </div>
            <div class="text-left collapse" id="responsibleList">
                <?php
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="row members-select-responsible <?= ($n['id'] == $worker) ? 'd-none' : '' ?>">
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
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="add-worker <?= ($n['id'] == $coworker['worker_id']) ? '' : 'd-none' ?>">
                        <img src="/<?= getAvatarLink($n['id']) ?>"
                             class="avatar-added mr-1">
                        <span class="coworker-fio"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                        <i class="fas fa-times icon-newtask-delete-coworker"></i>
                    </div>
                <?php } ?>
            </div>
            <div class="text-left collapse" id="coworkersList">
                <?php
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="row members-coworker-select <?= ($n['id'] == $coworker['worker_id']) ? 'd-none' : '' ?>">
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

        function updateCoworkers() {
            $(".members-select-responsible:visible").each(function () {
                var list = $(this).attr('val');
                $('#coworkersList').find("[val = " + list + "]").removeClass('d-none');
            });
            $(".add-worker:visible").each(function () {
                var list = $(this).attr('val');
                $('#coworkersList').find("[val = " + list + "]").addClass('d-none');
            })
        }

        function updateResponsible() {
            $(".members-coworker-select:visible").each(function () {
                var list = $(this).attr('val');
                $('#responsibleList').find("[val = " + list + "]").removeClass('d-none');
            });
        }

        //работа с ответственными
        $(".members-select-responsible").on('click', function () {
            var id = $(this).attr('val');
            var selected = $('.members-responsible-selected:visible').attr('val');
            $("#responsibleList").find("[val = " + selected + "]").removeClass('d-none');
            $(this).addClass('d-none');
            $(".members-responsible-selected").addClass('d-none');
            $('#coworkersList').find("[val = " + id + "]").addClass('d-none');
            $('.container-coworker').find("[val = " + id + "]").addClass('d-none');
            $(".container-members-responsible-selected").find("[val = " + id + "]").removeClass('d-none');
            updateCoworkers();
        });

        //работа с соисполнителями
        $(".members-coworker-select").on('click', function () {
            var id = $(this).attr('val');
            $(this).addClass('d-none');
            $('#responsibleList').find("[val = " + id + "]").addClass('d-none');
            $('.container-coworker').find("[val = " + id + "]").removeClass('d-none');
            updateResponsible();
        });

        $('.add-worker').on('click', function () {
            var id = $(this).attr('val');
            $(this).addClass('d-none');
            $('#coworkersList').find("[val = " + id + "]").removeClass('d-none');
            updateResponsible();
        });

        $("#confirmMembers").on('click', function () {
            var responsible = $('.members-responsible-selected:visible').attr('val');
            var coworkers = [];
            $('.add-worker:visible').each(function () {
                coworkers.push($(this).attr('val'));
            });
            console.log(responsible);
            console.log(coworkers);
            var fd = new FormData();
            fd.append('module', 'addCoworker');
            fd.append('worker', responsible);
            fd.append('coworkers', JSON.stringify(coworkers));
            fd.append('ajax', 'task-control');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    console.log('asd');
                },
            });
            $('.members').fadeOut(200);
        });
    });
</script>
