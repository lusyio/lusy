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
        <?php $uploadModule = 'task'; // Указываем тип дропдауна прикрепления файлов?>
        <?php include 'engine/frontend/other/upload-module.php'; // Подключаем дропдаун прикрепления файлов?>
        <?php if ($tariff == 1): // БЛОК ДЛЯ ПРЕМИУМ ТАРИФА?>
        <div class="row mt-2">
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label>
                        Дата начала
                    </label>
                    <input type="date" class="form-control" id="startDate" min="<?= $GLOBALS["now"] ?>"
                           value="<?= $GLOBALS["now"] ?>" required>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <label>
                        Дата окончания
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
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>" class="add-responsible d-none">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="card-coworker"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
                    $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                    foreach ($users as $n) { ?>
                        <div val="<?php echo $n['id'] ?>" class="add-worker d-none">
                            <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                            <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
        <?php else: // БЛОК ДЛЯ БЕСПЛАТНОГО ТАРИФА?>
            <div class="row mt-2">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label>
                            Дата окончания
                        </label>
                        <input type="date" class="form-control" id="datedone" min="<?= $GLOBALS["now"] ?>"
                               value="<?= $GLOBALS["now"] ?>" required>
                    </div>
                </div>
                <div class="col-12 col-md-8 coworkers-newtask">
                    <label><?= $GLOBALS['_responsiblenewtask'] ?></label>
                    <div class="container container-responsible d-flex flex-wrap align-content-sm-stretch"
                         style="min-height: 38px">
                        <div class="text-muted placeholder-responsible"><?= $GLOBALS['_placeholderresponsiblenewtask'] ?></div>
                        <?php
                        $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                        foreach ($users as $n) { ?>
                            <div val="<?php echo $n['id'] ?>" class="add-responsible d-none">
                                <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                                <span class="card-coworker"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
                        $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
                        foreach ($users as $n) { ?>
                            <div val="<?php echo $n['id'] ?>" class="add-worker d-none">
                                <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                                <span class="coworker-fio"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
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
        <?php endif; ?>
        <!-- Divider -->
        <hr class="mt-4 mb-4">
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
<script src="/assets/js/createtask.js?ver=1"></script>
<?php if ($tariff == 1):?>
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
        gapi.load('picker', '1', { callback: this.PickerApiLoaded.bind(this) });
    }
    FilePicker.prototype = {
        //==========Check Authentication & Call ShowPicker() function=======
        open: function () {
            var token = gapi.auth.getToken();
            if (token) {
                this.ShowPicker();
            } else {
                this.DoAuth(false, function ()
                { this.ShowPicker(); }.bind(this));
            }
        },
        //========Show the file picker once authentication has been done.=========
        ShowPicker: function () {
            var accessToken = gapi.auth.getToken().access_token;
            var DisplayView = new google.picker.DocsView().setIncludeFolders(true);
            this.picker = new google.picker.PickerBuilder().
            addView(DisplayView).
            enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
            setAppId(this.clientId).
            setOAuthToken(accessToken).
            setCallback(this.PickerResponse.bind(this)).
            setTitle('Google Drive').
            setLocale('ru').
            build().
            setVisible(true);
        },
        //====Called when a file has been selected in the Google Picker Dialog Box======
        PickerResponse: function (data) {
            if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                var gFiles = data[google.picker.Response.DOCUMENTS];
                gFiles.forEach(function (file) {
                    console.log(file);
                    addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive' );
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
        success: function(files) {
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
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="pjjm32k7twiooo2"></script>
<?php endif; ?>
<script>
    $(document).ready(function () {
        $("#startDate").on('change', function () {
           var val = $(this).val();
           $('#datedone').attr('min', val);
           if (val > $('#datedone').val()){
               $('#datedone').val(val);
           }
        });
        <?php if ($tariff == 0):?>
        $('#openGoogleDrive, #openDropbox').attr('data-target', '#premModal');
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
        placeholder: 'Опишите суть задания',
    });


</script>