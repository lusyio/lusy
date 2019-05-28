<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<h2 class="header-pretitle pb-3">
    Создайте новую задачу
</h2>
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
        <div class="row">
            <div class="col-sm-4">
                <span class="btn btn-light btn-file border">
                    <i class="fas fa-file-upload custom-date mr-2"></i><span class="attach-file text-muted">Выберите файл</span><input
                            id="sendFiles" type="file" multiple>
                </span>
            </div>
            <div class="col-sm">
                <div style="display: none"
                     class="bg-white file-name container p-1 container-coworker flex-wrap align-content-sm-stretch">
                </div>
            </div>
        </div>
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
                <label>Ответственный/соисполнитель</label>
                <div class="row coworker-item">
                    <div class="form-group col-10 p-0">
                        <select class="form-control coworker-select" id="worker" required>
                            <?php
                            $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
                            foreach ($users as $n) { ?>
                                <option value="<?php echo $n['id'] ?>"><?php echo $n['name'] . ' ' . $n['surname'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-2 p-0">
                        <button class="btn btn-primary h-100 coworker-button" button-action="add">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="col-sm">
                        <button class="btn btn-light newtask-change-coworkers" data-toggle="collapse"
                                data-target="#c workersList" aria-expanded="false" aria-controls="coworkersList">
                            Изменить
                        </button>
                        <?php
                        include 'engine/frontend/members/members.php';
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <hr class="mt-4 mb-5">


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
    <script src="/assets/js/createtask.js"></script>
    <script>
        $(document).ready(function () {

            $(".newtask-change-coworkers").on('click', function (e) {
                $(".members").fadeToggle(300);
            });

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