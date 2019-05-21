<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">

            <!-- Header -->
            <div class="header mt-md-5">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">

                            <!-- Pretitle -->
                            <h2 class="header-pretitle">
                                Создайте новую задачу
                            </h2>

                            <!-- Title -->
                            <h4 class="header-title" id="headerName">
                                Новая задача
                            </h4>

                        </div>
                    </div> <!-- / .row -->
                </div>
            </div>

            <!-- Form -->


            <!-- Project id -->
            <div class="mb-2">
                <!--                <label>-->
                <!--                  Имя задачи-->
                <!--                </label>-->
                <input type="text" id="name" class="form-control" placeholder="Наименование задачи" required>
            </div>

            <!-- Project id -->
            <!--              <div class="form-group">-->
            <!--                <label>-->
            <!--                  Описание задачи-->
            <!--                </label>-->
            <!--                <textarea class="form-control" id="description" s="3" placeholder="Опишите суть задания"  required></textarea>-->
            <!--              </div>-->
            <div id="editor" class="mb-2">
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <span class="btn btn-light btn-file border">
                        <i class="fas fa-file-upload custom-date mr-2"></i><span class="attach-file text-muted">Выберите файл</span><input
                                type="file">
                    </span>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12 col-md-6">
                    <!-- Project id -->
                    <div class="form-group">
                        <label>
                            Дата окончания
                        </label>
                        <input type="date" class="form-control" id="datedone" min="<?= $GLOBALS["now"] ?>"
                               value="<?= $GLOBALS["now"] ?>" required>
                    </div>
                </div>
                <div class="col-12 col-md-6 coworkers-newtask">
                    <div class="row">
                        <label>Ответственные</label>
                    </div>
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
                    </div>
                </div>
            </div>
            <!-- Divider -->
            <hr class="mt-4 mb-5">


            <!-- не начислять баллы -->
            <!-- Buttons -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <button id="createTask" class="btn btn-block btn-primary h-100">Создать задачу</button>
                </div>
            </div>
            <!--              <button id="createTask" class="btn btn-block btn-primary w-85 d-inline">Создать задачу</button>-->
            <!--              <span class="btn btn-light btn-file">-->
            <!--                    <i class="fas fa-file-upload custom-date"></i><input type="file">-->
            <!--                </span>-->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <a href="/" class="btn btn-block btn-link text-muted">
                        Отменить создание задачи
                    </a>
                </div>
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