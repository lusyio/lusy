<div id="avatarNew">
    <span class="position-absolute edit-members">
        <i class="fas fa-plus avatar-new" style="font-size: 17px;"></i>
    </span>
    <div class="members">
        <div class="members-card position-relative">
            <div class="text-justify owner">
                <img src="/<?= getAvatarLink($manager) ?>"
                     class="avatar-added mr-1">
                <?php
                if ($task['managerName'] != null || $task['managerSurname'] != null): ?>
                    <a href="#"><?= $task['managerName'] ?> <?= $task['managerSurname'] ?></a>
                <?php else: ?>
                    <a href="#"><?= $task['managerEmail'] ?></a>
                <?php endif; ?>
            </div>
            <hr class="m-0">
            <div class="members-responsible">
                <div class="row" style="padding: 5px;">
                    <div class="col text-left">
                        <span>Ответственный</span>
                    </div>
                    <?php if ($isCeo || $role == 'manager'): ?>
                        <div class="col-2 text-right">
                            <i class="fas fa-pencil-alt icon-members-change-responsible" data-toggle="collapse"
                               data-target="#responsibleList" aria-expanded="false" aria-controls="responsibleList"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <hr class="mt-0 mb-1">
                <div class="container-members-responsible-selected">
                    <?php
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>"
                             class="row members-responsible-selected <?= ($n['id'] == $worker) ? '' : 'd-none' ?>">
                            <div class="col-1">
                                <img src="/<?= getAvatarLink($n['id']) ?>"
                                     class="avatar-added mr-1">
                            </div>
                            <div class="col text-left">
                                <span><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
                                <span class="add-coworker-text"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
                    <?php if ($isCeo || $role == 'manager'): ?>
                        <div class="col-2 text-right">
                            <i class="fas fa-pencil-alt icon-members-change-coworker" data-toggle="collapse"
                               data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-1 container p-1 container-coworker d-flex flex-wrap align-content-sm-stretch">
                    <?php if ($isCeo || $role == 'manager'): ?>
                        <div class="text-muted placeholder-coworkers" data-toggle="collapse"
                             data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList">Нажмите,
                            чтобы добавить
                        </div>
                    <?php else: ?>
                        <div class="text-muted placeholder-coworkers">
                            Список пуст
                        </div>
                    <?php endif; ?>
                    <?php
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>"
                             class="add-worker <?= (in_array($n['id'], $coworkersId)) ? '' : 'd-none' ?>">
                            <img src="/<?= getAvatarLink($n['id']) ?>"
                                 class="avatar-added mr-1">
                            <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                            <?php if ($isCeo || $role == 'manager'): ?>
                                <i class="fas fa-times icon-newtask-delete-coworker"></i>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="text-left collapse" id="coworkersList">
                    <div class="empty-list text-muted text-center">
                        Список пуст
                    </div>
                    <?php
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>"
                             class="row members-coworker-select <?= (in_array($n['id'], $coworkersId)) ? 'd-none' : '' ?>">
                            <div class="col-1">
                                <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added">
                            </div>
                            <div class="col">
                                <span class="add-coworker-text"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                            </div>
                            <div class="col-2 text-right">
                                <i class="fas fa-plus icon-add-coworker"></i>
                            </div>
                        </div>
                    <?php } ?>
                    <hr class="mt-1 mb-1">
                </div>
                <?php if ($isCeo || $role == 'manager'): ?>
                    <div class="mt-3 text-center">
                        <button class="btn btn-primary btn-sm" id="confirmMembers" type="button">Сохранить</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $(".avatar-new").on('click', function (e) {
            $(".members").fadeToggle(200);
        });

        $(document).on('click', function (e) { // событие клика по веб-документу
            var div = $("#avatarNew"); // тут указываем ID элемента
            var dov = $('.members');
            if (!div.is(e.target) && !dov.is(e.target) // если клик был не по нашему блоку
                && div.has(e.target).length === 0) { // и не по его дочерним элементам
                dov.fadeOut(200); // скрываем его
            }
        });

        function updateCoworkers() {
            var id = $('.members-responsible-selected:visible').attr('val');
            $('#coworkersList').find("[val = " + id + "]").addClass('d-none');
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

        $('.icon-members-change-coworker').on('click', function () {
            setTimeout(function () {
                updateCoworkers();
                checkContainerCoworkers();
                coworkersListEmpty();
            }, 100);
        });

        $(".edit-members").on('click', function () {
            checkContainerCoworkers();
        });

        function checkContainerCoworkers() {
            if ($(".add-worker").is(':visible')) {
                $('.placeholder-coworkers').hide();
            } else {
                $('.placeholder-coworkers').show();
            }
        }

        function coworkersListEmpty() {
            if ($(".members-coworker-select").is(':visible')) {
                $('.empty-list').hide();
            } else {
                $('.empty-list').show();
            }
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
            checkContainerCoworkers();
            coworkersListEmpty();
        });

        //работа с соисполнителями
        $(".members-coworker-select").on('click', function () {
            var id = $(this).attr('val');
            $(this).addClass('d-none');
            // $('#responsibleList').find("[val = " + id + "]").addClass('d-none');
            $('.container-coworker').find("[val = " + id + "]").removeClass('d-none');
            updateResponsible();
            checkContainerCoworkers();
            coworkersListEmpty();
        });

        $('.add-worker').on('click', function () {
            if ($('.add-worker').children('.icon-newtask-delete-coworker').length > 0) {
                var id = $(this).attr('val');
                $(this).addClass('d-none');
                $('#coworkersList').find("[val = " + id + "]").removeClass('d-none');
                updateResponsible();
                checkContainerCoworkers();
                coworkersListEmpty();
            }
        });

        $("#confirmMembers").on('click', function () {
            var responsible = $('.members-responsible-selected:visible').attr('val');
            var coworkers = [];
            $('.add-worker:visible').each(function () {
                coworkers.push($(this).attr('val'));
            });
            var fd = new FormData();
            fd.append('module', 'addCoworker');
            fd.append('worker', responsible);
            fd.append('coworkers', JSON.stringify(coworkers));
            fd.append('ajax', 'task-control');
            fd.append('it', $it);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    location.reload();
                },
            });
        });
    });
</script>
