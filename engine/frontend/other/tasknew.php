<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<div class="card new-task-container">
    <div class="card-body">
        <div class="row mb-2 md-5">
            <div class="col">
                <div class="header">
                    <h4 class="header-title" id="headerName">
                        <?= $GLOBALS['_newtask'] ?>
                    </h4>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <input type="text" id="name" class="form-control" placeholder="<?= $GLOBALS['_namenewtask'] ?>" autocomplete="off" autofocus required>
        </div>
        <div id="editor" class="mb-2">
        </div>
        <div style="display: none"
             class="bg-white file-name container-files">
        </div>
        <span class="btn btn-light btn-file border d-none">
            <i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file text-muted"><?= $GLOBALS['_choosefilenewtask'] ?></span>
            <input id="sendFiles" type="file" multiple>
        </span>
        <div class="dropdown">
            <button class="btn btn-light btn-file border dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Прикрепить файл
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
                    <span class="attach-file">С компьютера</span></a>
                <a class="dropdown-item" id="openYaDisk" href="#" data-toggle="modal" data-target="#yaDiskModal"><i class="custom-date mr-2 fab fa-yandex"></i>
                    <span>Из Яндекс.Диска</span></a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>
                        <?= $GLOBALS['_deadlinenewtask'] ?>
                    </label>
                    <input type="date" class="form-control" id="datedone" min="<?= $GLOBALS["now"] ?>"
                           value="<?= $GLOBALS["now"] ?>" required>
                </div>
            </div>
            <div class="col-12 col-md-6 coworkers-newtask">
                <label><?= $GLOBALS['_responsiblenewtask'] ?></label>
                <div class="container container-responsible d-flex flex-wrap align-content-sm-stretch"
                     style="min-height: 38px">
                    <div class="text-muted placeholder-responsible"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                    <?php
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>" class="add-responsible d-none">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="card-coworker"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                        </div>
                        <hr class="m-0">
                    <?php } ?>
                    <div class="position-absolute icon-newtask icon-newtask-change-responsible">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>
                <?php
                include 'engine/frontend/members/responsible.php';
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col coworkers-toggle">
                <label><?= $GLOBALS['_coworkersnewtask'] ?></label>
                <div class="container container-coworker d-flex flex-wrap align-content-sm-stretch">
                    <div class="text-muted placeholder-coworkers"><?= $GLOBALS['_placeholdercoworkersnewtask'] ?></div>
                    <?php
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>" class="add-worker d-none">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="coworker-fio"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                            <i class="fas fa-times icon-newtask-delete-coworker"></i>
                        </div>
                    <?php } ?>
                    <div class="position-absolute icon-newtask icon-newtask-add-coworker">
                        <i class="fas fa-caret-down"></i>
                    </div>
                </div>
                <?php
                include 'engine/frontend/members/coworkers.php';
                ?>
            </div>
        </div>

        <!-- Divider -->
        <hr class="mt-4 mb-4">


        <!-- не начислять баллы -->
        <!-- Buttons -->
        <div class="row">
            <div class="col-sm p-0 create-task">
                <button id="createTask" class="btn btn-block btn-primary h-100"><?= $GLOBALS['_createnewtask'] ?></button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 p-0">
                <a href="/" class="btn btn-block btn-link text-muted">
                    <?= $GLOBALS['_cancelnewtask'] ?>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="yaDiskModal" tabindex="-1" role="dialog" aria-labelledby="yaDiskModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="yaDiskModalTitle">Выберите файл в Яндекс.Диске</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="yaDiskContent">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="addYandexFiles" class="btn btn-primary" disabled>Выбрать</button>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/createtask.js"></script>
<script>
    var yt = '<?= $yandexToken ?>';
    $(document).ready(function () {
        $("#name").on('input', function () {
            var nameText = $('#name').val();
            var header = $("#headerName");
            if (nameText) {
                header.html(nameText);
            } else {
                nameText = 'Введите название задачи';
                header.html(nameText);
            }
        });

        $('#openYaDisk').on('click', function () {
            if (yt !== '') {
                getYandexFiles(yt)
            } else {
                window.open('https://oauth.yandex.ru/authorize?response_type=token&client_id=e2e6c4743f6c470493e48748a09b2c3c&display=popup', '', 'width=600,height=800,toolbar=no,menubar=no');
            }
        });


        $('#yaDiskModal').on('hide.bs.modal', function () {
            $('#yaDiskContent').empty();
        })
    });
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Опишите суть задания',
    });

    $('#yaDiskModal').on('click', '.ya-file', function () {
        var el = $(this);
       if (el.hasClass('bg-primary')) {
           el.removeClass('bg-primary')
           if ($('.ya-file .bg-primary').length == 0) {
               $('#addYandexFiles').attr('disabled', true);
           }
       } else {
           el.addClass('bg-primary')
           $('#addYandexFiles').attr('disabled', false);
       }

    });

    $('#addYandexFiles').on('click', function () {
        $('#yaDiskModal .bg-primary').each(function (i, fileToAdd) {
            $(".file-name").show().append("<div class='filenames attached-ya-file' data-name='" + $(fileToAdd).data('file-name') + "' data-link='" + $(fileToAdd).data('link') + "'>" +
                "<i class='fas fa-paperclip mr-1'></i> <i class='fab fa-yandex mr-1'></i>" + $(fileToAdd).data('file-name') +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
        });
        $('#yaDiskModal').modal('hide');
    });

    function getYandexFiles(token) {
        $.ajax({
            url: 'https://cloud-api.yandex.net/v1/disk/resources/files',
            type: 'GET',
            accept: 'application/json',
            contentType: 'application/json',
            cache: false,
            beforeSend: function (request) {
                request.setRequestHeader("Authorization", 'OAuth ' + token);
            },
            processData: true,
            data: {
                limit: 15
            },
            success: function (response) {
                $(response.items).each(function (i, el) {
                    $('#yaDiskContent').append('<div class="ya-file" data-link="' + el.file + '" data-file-name="' + el.name + '">' + el.name + '</div>');
                })
            },
        });
    }
</script>