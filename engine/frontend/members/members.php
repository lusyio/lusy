<?php
$users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
?>
<div id="avatarNew">
    <span class="position-absolute edit-members">
        <i class="fas fa-plus avatar-new"></i>
    </span>
    <div class="members">
        <div class="members-card position-relative">
            <div class="row p-5px">
                <div class="col text-left text-muted">
                    <span>Постановщик</span>
                </div>
            </div>
            <div class="text-justify owner">
                <img src="/<?= getAvatarLink($manager) ?>" class="avatar-added mr-1 mb-0">
                <a class="text-decoration-none" href="/profile/<?= $manager ?>/"><?= $managerName ?></a>
            </div>
            <div class="members-responsible">
                <div class="row p-5px">
                    <div class="col text-left">
                        <span class="text-muted">Ответственный</span>
                    </div>
                    <?php if (($status != 'done' && $status != 'canceled') && ($isCeo || $role == 'coworker') && count($users) > 1 && $repeatType < 1): ?>
                        <div class="col-2 text-right">
                            <i class="fas fa-pencil-alt icon-members-change-responsible" data-toggle="collapse"
                               data-target="#responsibleList" aria-expanded="false" aria-controls="responsibleList"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="container-members-responsible-selected">
                    <?php foreach ($users as $n): ?>
                        <div val="<?php echo $n['id'] ?>" class="row members-responsible-selected <?= ($n['id'] == $worker) ? '' : 'd-none' ?>">
                            <div class="col-1">
                                <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added mr-1">
                            </div>
                            <div class="col text-left">
                                <span><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                <div class="text-left collapse" id="responsibleList">
                <?php foreach ($users as $n): ?>
                        <div val="<?php echo $n['id'] ?>" class="row members-select-responsible <?= ($n['id'] == $worker) ? 'd-none' : '' ?>">
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
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="members-coworkers">
            <?php if (($status != 'done' && $status != 'canceled') && ($isCeo || $role == 'manager') && $manager != 1 && count($users) > 1 && $repeatType < 1): ?>
                <div class="row p-5px">
                    <div class="col text-justify">
                        <span class="text-muted">Соисполнители</span>
                    </div>
                    <div class="col-2 text-right">
                        <i class="fas fa-pencil-alt icon-members-change-coworker" data-toggle="collapse"
                           data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList"></i>
                    </div>
                </div>
                <div class="mb-1 container p-1 container-coworker d-flex flex-wrap align-content-sm-stretch">
                    <div class="text-muted placeholder-coworkers" data-toggle="collapse"
                         data-target="#coworkersList" aria-expanded="false" aria-controls="coworkersList">Нажмите,
                        чтобы добавить
                    </div>
                    <?php foreach ($users as $n): ?>
                    <div val="<?php echo $n['id'] ?>"
                         class="add-worker <?= (in_array($n['id'], $coworkersId)) ? '' : 'd-none' ?>">
                        <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added mr-1">
                        <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                        <i class="fas fa-times icon-newtask-delete-coworker"></i>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="container-members-responsible-selected coworkers-list">
                <?php if(count($coworkersId) != 0): ?>
                    <div class="row p-5px">
                        <div class="col text-justify">
                            <span class="text-muted">Соисполнители</span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach ($users as $n): ?>
                    <?php if (in_array($n['id'], $coworkersId)):?>
                    <div val="<?php echo $n['id'] ?>" class="row members-responsible-selected">
                        <div class="col-1">
                            <img src="/<?= getAvatarLink($n['id']) ?>" class="avatar-added mr-1">
                        </div>
                        <div class="col text-left">
                            <span><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
            <div class="text-left collapse" id="coworkersList">
                <div class="empty-list text-muted text-center">
                    Список пуст
                </div>
                <?php foreach ($users as $n): ?>
                <?php
                if ($n['id'] == $id) {
                    continue;
                } ?>
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
                <?php endforeach; ?>
            </div>
            <?php if (($status != 'done' && $status != 'canceled') && ($isCeo || $role == 'manager') && count($users) > 1 && $repeatType < 1): ?>
            <div class="mt-3 text-center">
                <button class="btn btn-primary btn-sm" id="confirmMembers" type="button">Сохранить</button>
            </div>
            <?php endif; ?>
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

            $('.placeholder-coworkers').on('click', function () {
                setTimeout(function () {
                    updateCoworkers();
                    checkContainerCoworkers();
                    coworkersListEmpty();
                }, 100);
            });

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
                $(this).prop('disabled', true);
                $('#spinnerModal').modal('show');
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
