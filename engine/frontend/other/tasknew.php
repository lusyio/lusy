<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<div class="card new-task-container">
    <div class="card-body">
        <div class="row mb-2 md-5">
            <div class="col">
                <div class="header">
                    <h4 class="header-title" id="headerName">
                        Новая задача
                    </h4>
                </div>
            </div>
        </div>
        <div class="mb-2">
            <input type="text" id="name" class="form-control" placeholder="Наименование задачи" required>
        </div>
        <div id="editor" class="mb-2">
        </div>
        <div style="display: none"
             class="bg-white file-name container-files">
        </div>
        <span class="btn btn-light btn-file border">
            <i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file text-muted">Выберите файл</span>
            <input id="sendFiles" type="file" multiple>
        </span>
        <div class="row mt-2">
            <div class="col-12 col-md-6">
                <div class="form-group">
                    <label>
                        Дата окончания
                    </label>
                    <input type="date" class="form-control" id="datedone" min="<?= $GLOBALS["now"] ?>"
                           value="<?= $GLOBALS["now"] ?>" required>
                </div>
            </div>
            <div class="col-12 col-md-6 coworkers-newtask">
                <label>Ответственный</label>
                <div class="container container-responsible d-flex flex-wrap align-content-sm-stretch"
                     style="min-height: 38px">
                    <div class="text-muted placeholder-responsible">Выберите ответственного</div>
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
                <label>Соисполнители</label>
                <div class="container container-coworker d-flex flex-wrap align-content-sm-stretch">
                    <div class="text-muted placeholder-coworkers">Выберите соисполнителя</div>
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
                <button id="createTask" class="btn btn-block btn-primary h-100">Создать задачу</button>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 p-0">
                <a href="/" class="btn btn-block btn-link text-muted">
                    Отменить создание задачи
                </a>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/createtask.js"></script>
<script>
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
        })
    });
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Опишите суть задания',
    });
</script>