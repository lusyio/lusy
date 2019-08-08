<link href="/assets/css/quill.snow.css" rel="stylesheet">
<script type="text/javascript" src="/assets/js/quill.js"></script>
<?php
$borderColor = [
    'new' => 'border-primary',
    'inwork' => 'border-primary',
    'overdue' => 'border-danger',
    'postpone' => 'border-warning',
    'pending' => 'border-warning',
    'returned' => 'border-primary',
    'done' => 'border-success',
    'canceled' => 'border-secondary',
    'planned' => 'border-info',
];
?>

<div class="row">
    <div class="col-12 col-lg-8 top-block-tasknew">
        <label class="label-tasknew">
            Новая задача
        </label>
        <div class="mb-2 card card-tasknew">
            <input type="text" id="name" class="form-control border-0 card-body-tasknew"
                   placeholder="<?= $GLOBALS['_namenewtask'] ?>"
                   autocomplete="off" value="<?= ($taskEdit) ? $taskData['name'] : '' ?>" autofocus required>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <label class="label-tasknew">
            Дата окончания
        </label>
        <div class="mb-2 card card-tasknew">
            <input type="date" class="form-control border-0 card-body-tasknew"
                   id="datedone"
                   min="<?= $GLOBALS["now"] ?>"
                   value="<?= ($taskEdit) ? date('Y-m-d', $taskData['datedone']) : $GLOBALS["now"] ?>" required>
        </div>
    </div>
</div>

<div class="row mt-25-tasknew">
    <div class="col-12 col-lg-8 top-block-tasknew">
        <label class="label-tasknew">
            Описание задачи
        </label>
        <div class="mb-2 card card-tasknew editor-card">
            <div id="editor" class="border-0">
                <?= ($taskEdit) ? htmlspecialchars_decode($taskData['description']) : '' ?>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <label class="label-tasknew">
            Участники
        </label>
        <?php
        include __ROOT__ . '/engine/frontend/members/responsible.php';
        ?>
        <div class="mb-2 card card-tasknew card-tasknew-minheight">
            <label class="label-responsible">
                <?= $GLOBALS['_responsiblenewtask'] ?>
            </label>
            <div class="container container-responsible border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
                <div class="placeholder-responsible"
                     style="<?= ($taskEdit) ? 'display: none;' : '' ?>"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                <?php
                foreach ($users

                as $n) { ?>
                <div val="<?php echo $n['id'] ?>"
                     class="add-responsible <?= ($taskEdit && $n['id'] == $taskData['worker']) ? 'responsible-selected' : 'd-none' ?>">
                <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                <span class="card-coworker"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
            </div>
            <?php } ?>
            <div class="position-absolute icon-newtask icon-newtask-change-responsible">
                <i class="fas fa-caret-down"></i>
            </div>
        </div>


        <label class="label-responsible">
            <?= $GLOBALS['_coworkersnewtask'] ?>
        </label>
        <div class="coworkers-toggle container container-coworker border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
            <?php foreach ($users as $n) { ?>
                <div val="<?php echo $n['id'] ?>"
                     class="add-worker <?= ($taskEdit && in_array($n['id'], $taskCoworkers)) ? '' : 'd-none' ?>">
                    <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                    <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                    <i class="fas fa-times icon-newtask-delete-coworker"></i>
                </div>
            <?php } ?>
            <div class="placeholder-coworkers position-relative">
                Добавить
                <div class="position-absolute icon-newtask icon-newtask-add-coworker">
                    <i class="fas fa-caret-down"></i>
                </div>
            </div>
        </div>

    </div>
    <?php
    include __ROOT__ . '/engine/frontend/members/coworkers.php';
    ?>

</div>
</div>

<div class="row mt-25-tasknew">
    <div class="col-12 text-center position-relative">
        <div class="other-func text-center position-relative">
            <div class="additional-func">
                <span>Дополнительные функции <i class="fas fa-caret-down"></i></span>
            </div>
        </div>
        <?php if ($tariff == 1 || $tryPremiumLimits['task'] < 3 || $taskEdit): // БЛОК ДЛЯ ПРЕМИУМ ТАРИФА?>
            <div class="collapse mt-25-tasknew" id="collapseFunctions">
                <div class="row top-block-tasknew">
                    <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                        <div class="label-tasknew text-left">
                            Надзадача
                            <?php if (($tryPremiumLimits['task'] < 3 && $tariff == 0) || ($taskEdit && $tariff == 0 && $taskData['with_premium'] == 0)): ?>
                                <span class="tooltip-free" data-toggle="tooltip" data-placement="bottom"
                                      title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['task'] ?>/3"><i
                                            class="fas fa-comment-dollar"></i></span>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="card card-tasknew">
                            <?php
                            include __ROOT__ . '/engine/frontend/members/subtask.php';
                            ?>
                            <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew">
                                <div class="placeholder-subtask"
                                     style="<?= ($taskEdit && !is_null($taskData['parent_task'])) ? 'display: none;' : '' ?>">
                                    Не выбрана
                                </div>
                                <?php
                                foreach ($parentTasks as $parentTask) { ?>
                                    <div val="<?php echo $parentTask['id']; ?>"
                                         class="add-subtask text-area-message <?= ($taskEdit && $parentTask['id'] == $taskData['parent_task']) ? 'subtask-selected' : 'd-none' ?> border-left-tasks <?= $borderColor[$parentTask['status']] ?>">
                                        <span class="card-coworker"><?= $parentTask['name']; ?></span>
                                    </div>
                                <?php } ?>
                                <div class="position-absolute icon-newtask icon-newtask-change-subtask">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="label-tasknew text-left">
                            Дата старта
                            <?php if (($taskEdit && $taskData['status'] == 'planned') || !$taskEdit): ?>
                                <?php if (!$taskEdit && ($tryPremiumLimits['task'] < 3 && $tariff == 0) || ($taskEdit && $tariff == 0 && $taskData['with_premium'] == 0)): ?>
                                    <span class="tooltip-free" data-toggle="tooltip" data-placement="bottom"
                                          title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['task'] ?>/3"><i
                                                class="fas fa-comment-dollar"></i></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="tooltip-free" data-toggle="tooltip" data-placement="bottom"
                                      title="Задача уже в работе"><i
                                            class="fas fa-info-circle"></i></span>
                            <?php endif; ?>
                        </div>
                        <div class="card card-tasknew">
                            <input type="date" class="form-control border-0 card-body-tasknew" id="startDate"
                                   <?= ($taskEdit && $taskData['status'] != 'planned') ? '' : 'min="' . $GLOBALS["now"] . '"' ?>
                                   value="<?= ($taskEdit) ? date('Y-m-d', $taskData['datecreate']) : $GLOBALS["now"]?>"
                                <?= ($taskEdit && $taskData['status'] != 'planned') ? 'disabled': 'required' ?>>
                        </div>
                    </div>
                </div>
                <div class="row mt-13px">
                    <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                        <div class="label-tasknew text-left">
                            Подпункты
                            <?php if (($tryPremiumLimits['task'] < 3 && $tariff == 0) || ($taskEdit && $tariff == 0 && $taskData['with_premium'] == 0)): ?>
                                <span class="tooltip-free" data-toggle="tooltip" data-placement="bottom"
                                      title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['task'] ?>/3"><i
                                            class="fas fa-comment-dollar"></i></span>
                            <?php
                            endif;
                            ?>
                        </div>
                        <div class="mb-2 card card-tasknew">
                            <input type="text" id="checklistInput" class="form-control border-0 card-body-tasknew"
                                   placeholder="Наименование подпункта"
                                   autocomplete="off">
                            <div id="addChecklistBtn" class="position-absolute icon-newtask">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="check-list-container card-body-tasknew text-left" style="<?= ($taskEdit && count($checklist) > 0) ? 'display: block;' : ''?>">
                                <div id="checkListExample" class="position-relative check-list-new d-none mb-2">
                                    <i class="far fa-check-square text-muted-new"></i>
                                    <span class="ml-3" style="color: #28416b;">  checkName  </span>
                                    <i class="fas fa-times delete-checklist-item"></i>
                                </div>
                                <?php if ($taskEdit): ?>
                                <?php foreach ($checklist as $key => $item): ?>
                                <div class="position-relative check-list-new mb-2 checklist-selected" data-id="<?= ++$key ?>">
                                    <i class="far fa-check-square text-muted-new"></i>
                                    <span class="ml-3" style="color: #28416b;"><?= $item['text'] ?></span>
                                    <i class="fas fa-times delete-checklist-item"></i>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="collapse mt-25-tasknew" id="collapseFunctions">
                <div class="row">
                    <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                        <div class="label-tasknew text-left">
                            Надзадача
                        </div>
                        <div class="card card-tasknew">
                            <span class="position-absolute disabledBtnOptions">
                            </span>
                            <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew disabled">
                                <div class="placeholder-subtask">Не выбрана</div>
                                <div class="position-absolute icon-newtask icon-newtask-change-subtask">
                                    <i class="fas fa-caret-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="label-tasknew text-left">
                            Дата старта
                        </div>
                        <div class="card card-tasknew">
                            <span class="position-absolute disabledBtnOptions">

                        </span>
                            <input type="date" class="form-control border-0 card-body-tasknew" id="startDate"
                                   min="<?= $GLOBALS["now"] ?>"
                                   value="<?= $GLOBALS["now"] ?>" required disabled>
                        </div>
                    </div>
                </div>
                <div class="row mt-13px">
                    <div class="col-12 col-lg-8 top-block-tasknew">
                        <div class="label-tasknew text-left">
                            Подпункты
                        </div>
                        <div class="mb-2 card card-tasknew">
                            <span class="position-absolute disabledBtnOptions">
                            </span>
                            <input type="text" id="checklistInput"
                                   class="form-control border-0 card-body-tasknew disabled"
                                   placeholder="Наименование подпункта"
                                   autocomplete="off">
                            <div id="addChecklistBtn" class="position-absolute icon-newtask">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="check-list-container card-body-tasknew text-left">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<div class="row mt-25-tasknew">
    <div class="col-12 col-lg-8 top-block-tasknew">
        <label class="label-tasknew">
            Прикрепленные файлы
        </label>
        <div class="spinner-border spinner-border-sm ml-1 display-none" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="file-name container-files <?= ($taskEdit && count($taskUploads) > 0) ? '' : 'display-none' ?>">
            <div id="filenamesExampleCloud" class='filenames attached-source-file d-none' data-name='name' data-link='link'
                 data-file-size='size' data-file-id="">
                <i class='fas fa-paperclip mr-1'></i> <i class='icon mr-1'></i>
                <span>name</span>
                <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
            </div>
            <div id="filenamesExample" val='n' class='filenames d-none'>
                <i class='fas fa-paperclip mr-1'></i>
                <span>filenames</span>
                <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
            </div>
            <?php if ($taskEdit): ?>
                <?php foreach ($taskUploads as $file): ?>
                    <?php if ($file['cloud']): ?>
                    <div class='filenames attached-source-file' data-name='<?= $file['file_name'] ?>' data-link='<?= $file['file_path'] ?>'
                         data-file-size='<?= $file['file_size'] ?>' data-file-id="<?= $file['file_id'] ?>">
                        <i class='fas fa-paperclip mr-1'></i> <i class='icon mr-1'></i>
                        <span><?= $file['file_name'] ?></span>
                        <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                    </div>
                    <?php else: ?>
                    <div val='n' data-file-id="<?= $file['file_id'] ?>" class='filenames device-uploaded'>
                        <i class='fas fa-paperclip mr-1'></i>
                        <span><?= $file['file_name'] ?></span>
                        <i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>
                    </div>
                    <?php endif;?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="pl-20-tasknew">
            <?php $uploadModule = 'tasknew'; // Указываем тип дропдауна прикрепления файлов?>
            <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; // Подключаем дропдаун прикрепления файлов?>
        </div>
    </div>
    <div class="col-lg-4 col-12">
    </div>
</div>


<div class="row createTask-row">
    <div class="col-12 col-lg-4 create-task">
        <button id="createTask"
                <?php if ($taskEdit && $tariff == 0 && !$taskData['with_premium']): ?>
                data-toggle="tooltip" data-placement="bottom" title="Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['edit'] ?>/3)"
                <?php endif; ?>
                class="btn btn-block btn-outline-primary h-100"><?= ($taskEdit) ? 'Сохранить' : $GLOBALS['_createnewtask'] ?></button>
    </div>
</div>

<div class="modal fade limit-modal" id="freeOptionsModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-left d-block">
                <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит использования расширенного
                    функционала задач в бесплатном тарифе</h5>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="text-left text-block">
                    <p class="text-muted-new">Так круто планировать задачи на будущее и тем самым разгружать себе голову
                        для будущих идей</p>
                    <p class="text-muted-new">Переходи на Premium тариф и тогда у тебя появится шанс обогнать Илона
                        Маска по капитализации</p>
                </div>
                <span class="position-absolute">
                <i class="fas fa-cogs icon-limit-modal"></i>
            </span>
            </div>
            <div class="modal-footer border-0">
                <?php if ($isCeo): ?>
                    <a href="/payment/" id="goToPay" class="btn text-white border-0">
                        Перейти к тарифам
                    </a>
                <?php endif; ?>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="modal fade limit-modal" id="premModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-left d-block">
                <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит загрузкок файлов через
                    облачные хранилища</h5>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="text-left text-block">
                    <p class="text-muted-new">Рады, что вы оценили бесшовную итеграцию с Google Drive и DropBox.</p>
                    <p class="text-muted-new">Переходи на Premium тариф и использую облака на полную мощность</p>
                </div>
                <span class="position-absolute">
                <i class="fas fa-cloud icon-limit-modal"></i>
            </span>
            </div>
            <div class="modal-footer border-0">
                <?php if ($isCeo): ?>
                    <a href="/payment/" id="goToPay" class="btn text-white border-0">
                        Перейти к тарифам
                    </a>
                <?php endif; ?>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="taskLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Лимит задач</h5>
            </div>
            <div class="modal-body text-center">
                Извините, у вас ичерпан лимит задач в этом месяце. Безлимитное число задач доступно в Premium
                версии.
            </div>
            <div class="modal-footer border-0">
                <?php if ($isCeo): ?>
                    <a href="/payment/" class="btn btn-primary">Перейти к тарифам</a>
                <?php endif; ?>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<div class="modal fade" id="spinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/createtask.js?n=2"></script>
<?php if (($tariff == 1 || $tryPremiumLimits['cloud'] < 3) || ($taskEdit && $hasCloudUploads)): ?>
    <script type="text/javascript">
        //=======================Google Drive==========================
        //=Create object of FilePicker Constructor function function & set Properties===
        function SetPicker() {
            var picker = new FilePicker(
                {
                    apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                    clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                    buttonEl: document.getElementById("openGoogleDrive"),
                    onClick: function (file) {
                    }
                });
        }

        //====================Create POPUP function==============
        function PopupCenter(url, title, w, h) {
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 2);
            return window.open(url, title, 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }

        //===============Create Constructor function==============
        function FilePicker(User) {
            //Configuration
            this.apiKey = User.apiKey;
            this.clientId = User.clientId;
            //Button
            this.buttonEl = User.buttonEl;
            //Click Events
            this.onClick = User.onClick;
            this.buttonEl.addEventListener('click', this.open.bind(this));
            //Disable the button until the API loads, as it won't work properly until then.
            this.buttonEl.disabled = true;
            //Load the drive API
            gapi.client.setApiKey(this.apiKey);
            gapi.client.load('drive', 'v2', this.DriveApiLoaded.bind(this));
            gapi.load('picker', '1', {callback: this.PickerApiLoaded.bind(this)});
        }

        FilePicker.prototype = {
            //==========Check Authentication & Call ShowPicker() function=======
            open: function () {
                var token = gapi.auth.getToken();
                if (token) {
                    this.ShowPicker();
                } else {
                    this.DoAuth(false, function () {
                        this.ShowPicker();
                    }.bind(this));
                }
            },
            //========Show the file picker once authentication has been done.=========
            ShowPicker: function () {
                var accessToken = gapi.auth.getToken().access_token;
                var DisplayView = new google.picker.DocsView().setIncludeFolders(true);
                this.picker = new google.picker.PickerBuilder().addView(DisplayView).enableFeature(google.picker.Feature.MULTISELECT_ENABLED).setAppId(this.clientId).setOAuthToken(accessToken).setCallback(this.PickerResponse.bind(this)).setTitle('Google Drive').setLocale('ru').build().setVisible(true);
            },
            //====Called when a file has been selected in the Google Picker Dialog Box======
            PickerResponse: function (data) {
                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                    var gFiles = data[google.picker.Response.DOCUMENTS];
                    gFiles.forEach(function (file) {
                        console.log(file);
                        addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive');
                    });
                }
            },
            //====Called when file details have been retrieved from Google Drive========
            GetFileDetails: function (file) {
                if (this.onClick) {
                }
            },
            //====Called when the Google Drive file picker API has finished loading.=======
            PickerApiLoaded: function () {
                this.buttonEl.disabled = false;
            },
            //========Called when the Google Drive API has finished loading.==========
            DriveApiLoaded: function () {
                this.DoAuth(true);
            },
            //========Authenticate with Google Drive via the Google Picker API.=====
            DoAuth: function (immediate, callback) {
                gapi.auth.authorize({
                    client_id: this.clientId,
                    scope: 'https://www.googleapis.com/auth/drive',
                    immediate: immediate
                }, callback);
            }
        };

        //=======================Dropbox==========================
        options = {
            success: function (files) {
                files.forEach(function (file) {
                    console.log(file);
                    addFileToList(file.name, file.link, file.bytes, 'dropbox', 'fab fa-dropbox');
                })
            },
            linkType: "direct", // or "preview"
            multiselect: true, // or false
            folderselect: false, // or true
        };
        $('#openDropbox').on('click', function () {
            Dropbox.choose(options);
        });

        //===================End of Dropbox=======================
        var n = 0;
        function addFileToList(name, link, size, source, icon) {
            n++;
            $('#filenamesExampleCloud').clone().addClass('attached-' + source + '-file').attr('data-name', name).attr('data-link', link).attr('data-file-size', size).attr('data-id', n).removeClass('d-none').appendTo('.file-name');
            $('[data-id=' + n + ']').find('span').text(name).find('mr-1').addClass(icon);
            $('.file-name').show();
            // $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "' data-file-size='" + size + "'>" +
            //     "<i class='fas fa-paperclip mr-1'></i> <i class='" + icon + " mr-1'></i>" + name +
            //     "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
            //     "</div>");
        }
    </script>
    <script src="https://www.google.com/jsapi?key=AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc"></script>
    <script src="https://apis.google.com/js/client.js?onload=SetPicker"></script>
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
            data-app-key="pjjm32k7twiooo2"></script>
<?php endif; ?>
<script>


    $(document).ready(function () {
        $('.other-func').on('click', function () {
            $('#collapseFunctions').collapse('toggle');
        });
        $('.disabledBtnOptions').on('click', function () {
            $('#freeOptionsModal').modal('toggle');
        });

        $("#datedone").on('change', function () {
            $(this).css('color', '#353b41');
            var minVal = $(this).attr('min');
            setTimeout(function () {
                if ($('#datedone').val() < minVal) {
                    $('#datedone').val(minVal);
                }
            }, 500);
        });
        $("#startDate").on('change', function () {
            $(this).css('color', '#353b41');
            var minVal = $(this).attr('min');
            setTimeout(function () {
                if ($('#startDate').val() < minVal) {
                    $('#startDate').val(minVal);
                }
            }, 500);
        });
        $("#startDate").on('change', function () {
            var val = $(this).val();
            var minVal = $(this).attr('min');
            if (val >= minVal) {
                $('#datedone').attr('min', val);
            }
            if (val > $('#datedone').val()) {
                $('#datedone').val(val);
            }
        });

        <?php if ($tariff == 0 && ($tryPremiumLimits['cloud'] >= 3 && !($taskEdit && !$hasCloudUploads))):?>
        $('#openGoogleDrive, #openDropbox').on('click', function () {
            $('#premModal').modal('show');
        });
        <?php endif; ?>

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
    });
    var quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Опишите суть задания...',
    });
    <?php if ($taskEdit): ?>
    var taskId = <?= $taskData['id']; ?>;
    var pageAction = 'edit';
    <?php else: ?>
    var taskId = 0;
    var pageAction = 'create';
    <?php endif; ?>
</script>