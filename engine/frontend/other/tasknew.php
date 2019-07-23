<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
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
                   style="height: 50px;"
                   placeholder="<?= $GLOBALS['_namenewtask'] ?>"
                   autocomplete="off" autofocus required>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <label class="label-tasknew">
            Дата окончания
        </label>
        <div class="mb-2 card card-tasknew">
            <input type="date" class="form-control border-0 card-body-tasknew"
                   style="height: 50px; font-size: 14px"
                   id="datedone"
                   min="<?= $GLOBALS["now"] ?>"
                   value="<?= $GLOBALS["now"] ?>" required>
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
        <div class="mb-2 card card-tasknew" style="min-height: 87.8%">
            <label class="label-responsible">
                <?= $GLOBALS['_responsiblenewtask'] ?>
            </label>
            <div class="container container-responsible border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew"
                 style="min-height: 38px;padding-top: 10px;">
                <div class="placeholder-responsible"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>" class="add-responsible d-none">
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
            <div class="coworkers-toggle container container-coworker border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew"
                 style="padding-top: 10px;">
                <?php
                $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                foreach ($users as $n) { ?>
                    <div val="<?php echo $n['id'] ?>" class="add-worker d-none">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                        <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                        <i class="fas fa-times icon-newtask-delete-coworker"></i>
                    </div>
                <?php } ?>
                <div class="placeholder-coworkers position-relative">
                    Не выбран
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
        <div class="other-func text-center position-relative" data-toggle="collapse" href="#collapseFunctions"
             role="button" aria-expanded="false" aria-controls="collapseFunctions">
            <div class="additional-func">
                <span>Дополнительные функции <i class="fas fa-caret-down"></i></span>
            </div>
        </div>
        <?php if ($tariff == 1 || $tryPremiumLimits['task'] < 3): // БЛОК ДЛЯ ПРЕМИУМ ТАРИФА?>
            <div class="collapse mt-25-tasknew" id="collapseFunctions">
                <div class="row">
                    <div class="col-12 col-lg-8 top-block-tasknew top-block-tasknew">
                        <div class="label-tasknew text-left">
                            Надзадача
                        </div>
                        <div class="card card-tasknew">
                            <?php
                            include __ROOT__ . '/engine/frontend/members/subtask.php';
                            ?>
<!--                            <span class="position-absolute disabledBtnOptions"-->
<!--                                  style="background-color: #000;width: 100%;bottom: 2px; height: 100%;z-index: 100000;opacity: 0;">-->
<!---->
<!--                            </span>-->
                            <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew"
                                 style="height: 50px;padding-top: 13px !important;">
                                <div class="placeholder-subtask">Не выбрана</div>
                                <?php
                                foreach ($parentTasks as $parentTask) { ?>
                                    <div val="<?php echo $parentTask['id']; ?>"
                                         class="add-subtask text-area-message d-none border-left-tasks <?= $borderColor[$parentTask['status']] ?>">
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
                        </div>
                        <div class="card card-tasknew">
                            <input type="date" class="form-control border-0 card-body-tasknew" id="startDate"
                                   style="height: 50px;font-size: 14px" min="<?= $GLOBALS["now"] ?>"
                                   value="<?= $GLOBALS["now"] ?>" required>
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
                            <span class="position-absolute disabledBtnOptions"
                                  style="background-color: #000;width: 100%;bottom: 2px; height: 100%;z-index: 100000;opacity: 0;">
                            </span>
                            <div class="container container-subtask border-0 d-flex flex-wrap align-content-sm-stretch card-body-tasknew disabled"
                                 style="height: 50px;padding-top: 13px !important;">
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
                            <span class="position-absolute disabledBtnOptions"
                                  style="background-color: #000;width: 100%;bottom: 2px; height: 52%;z-index: 100000;opacity: 0;">

                        </span>
                            <input type="date" class="form-control border-0 card-body-tasknew" id="startDate"
                                   style="height: 54px;font-size: 14px;" min="<?= $GLOBALS["now"] ?>"
                                   value="<?= $GLOBALS["now"] ?>" required disabled>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>

<div class="row mt-25-tasknew">
    <div class="col-12 col-lg-8 top-block-tasknew">
        <label class="label-tasknew">
            Прикрепленные файлы
        </label>
        <div class="spinner-border spinner-border-sm ml-1" role="status"
             style="color: #28416b; display: none">
            <span class="sr-only">Loading...</span>
        </div>
        <div style="display: none;padding-left: 30px;"
             class="file-name container-files">
        </div>
        <div style="padding-left: 20px;">
            <?php $uploadModule = 'tasknew'; // Указываем тип дропдауна прикрепления файлов?>
            <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; // Подключаем дропдаун прикрепления файлов?>
        </div>
    </div>
    <div class="col-lg-4 col-12">
    </div>
</div>


<div class="row" style="margin-top: 50px;">
    <div class="col-12 col-lg-4 create-task">
        <button id="createTask"
                class="btn btn-block btn-outline-primary h-100"><?= $GLOBALS['_createnewtask'] ?></button>
    </div>
</div>

<div class="modal fade" id="freeOptionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Дополнительные функции</h5>
            </div>
            <div class="modal-body text-center">
                Извините, Дополнительные функции доступны только в Premium версии.
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

<div class="modal fade" id="premModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-center d-block">
                <h5 class="modal-title" id="exampleModalLabel">Облачные хранилища</h5>
            </div>
            <div class="modal-body text-center">
                Извините, но функция загрузки файлов из облачных хранилищ доступна только в Premium версии
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
        <div class="modal-content border-0" style="margin-top: 60%;background-color: transparent;">
            <div class="modal-body text-center">
                <div class="spinner-border" style="width: 3rem; height: 3rem;color: #f2f2f2;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/createtask.js?n=2"></script>
<?php if ($tariff == 1 || $tryPremiumLimits['cloud'] < 3): ?>
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
        function addFileToList(name, link, size, source, icon) {
            $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "' data-file-size='" + size + "'>" +
                "<i class='fas fa-paperclip mr-1'></i> <i class='" + icon + " mr-1'></i>" + name +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
        }
    </script>
    <script src="https://www.google.com/jsapi?key=AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc"></script>
    <script src="https://apis.google.com/js/client.js?onload=SetPicker"></script>
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
            data-app-key="pjjm32k7twiooo2"></script>
<?php endif; ?>
<script>


    $(document).ready(function () {
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
            if (val > minVal) {
                $('#datedone').attr('min', val);
            }
            if (val > $('#datedone').val()) {
                $('#datedone').val(val);
            }
        });
        <?php if ($tariff == 0 || $tryPremiumLimits['cloud'] >= 3):?>
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


</script>