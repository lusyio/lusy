<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="pjjm32k7twiooo2"></script>
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
                <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i class="custom-date mr-2 fab fa-google-drive"></i>
                    <span>Из Google Drive</span></a>
                <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i class="custom-date mr-2 fab fa-dropbox"></i>
                    <span>Из Dropbox</span></a>
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
            <div class="modal-body">
                <div id="yaDiskContent">

                </div>
                <div id="yaControl" class="mt-2 text-center d-none">
                    <button type="button" id="yaPrev" class="btn btn-outline-secondary" disabled><</button>
                    <span id="yaPageNumber"></span>
                    <button type="button" id="yaNext" class="btn btn-outline-secondary">></button>
                </div>
            </div>
            <div class="modal-footer">
                <span>После создания задачи выбранные файлы станут публичными и будут доступны по ссылке</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="addYandexFiles" class="btn btn-primary" disabled>Выбрать</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //=Create object of FilePicker Constructor function function & set Properties===
    function SetPicker() {
        var picker = new FilePicker(
            {
                apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                buttonEl: document.getElementById("openGoogleDrive"),
                onClick: function (file) {
                    //PopupCenter('https://drive.google.com/file/d/' + file.id + '/view', "", 1026, 500);
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
            // Check if the user has already authenticated
            var token = gapi.auth.getToken();
            if (token) {
                this.ShowPicker();
            } else {
                // The user has not yet authenticated with Google
                // We need to do the authentication before displaying the drive picker.
                this.DoAuth(false, function ()
                { this.ShowPicker(); }.bind(this));
            }
        },
        //========Show the file picker once authentication has been done.=========
        ShowPicker: function () {
            var accessToken = gapi.auth.getToken().access_token;
            //========Show Different Display View in Picker Dialog box=======
            //View all the documents of Google drive
            //var DisplayView = new google.picker.View(google.picker.ViewId.DOCS);
            //View all the documents of a Specific folder of Google drive
            //var DisplayView = new google.picker.DocsView().setParent('PUT YOUR FOLDER ID');
            //View all the documents & folders of google drive
            var DisplayView = new google.picker.DocsView().setIncludeFolders(true);
            //Only view all Folders in Google drive.
            //var DisplayView = new google.picker.DocsView()
            //    .setIncludeFolders(true)
            //    .setMimeTypes('application/vnd.google-apps.folder')
            //    .setSelectFolderEnabled(true);
            //Use DocsUploadView to upload documents to Google Drive.
            //var UploadView = new google.picker.DocsUploadView();
            //addViewGroup(new google.picker.ViewGroup(google.picker.ViewId.DOCS).
            // addView(google.picker.ViewId.DOCUMENTS).
            // addView(google.picker.ViewId.PRESENTATIONS)).
            //========Show Different Upload View in Picker Dialog box=======
            //User can upload file in any folder (by select folder)
            //var UploadView = new google.picker.DocsUploadView().setIncludeFolders(true);
            //User can upload file in specific folder
            //var UploadView = new google.picker.DocsUploadView().setParent('PUT YOUR FOLDER ID')
            this.picker = new google.picker.PickerBuilder().
            addView(DisplayView).
            enableFeature(google.picker.Feature.MULTISELECT_ENABLED).
            setAppId(this.clientId).
            //addView(UploadView).
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
                    addFileToList(file.name, file.url, 'google-drive', 'fab fa-google-drive' );
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
</script>
<script src="https://www.google.com/jsapi?key=AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc"></script>
<script src="https://apis.google.com/js/client.js?onload=SetPicker"></script>

<script src="/assets/js/createtask.js"></script>
<script>
    var yt = '';
    var yaLimit = 15;
    var yaPage = 1;
    $(document).ready(function () {

        //=======================Dropbox==========================
        options = {

            // Required. Called when a user selects an item in the Chooser.
            success: function(files) {
                files.forEach(function (file) {
                    addFileToList(file.name, file.link, 'dropbox', 'fab fa-dropbox');
                })
            },

            // Optional. Called when the user closes the dialog without selecting a file
            // and does not include any parameters.
            cancel: function() {

            },

            // Optional. "preview" (default) is a preview link to the document for sharing,
            // "direct" is an expiring link to download the contents of the file. For more
            // information about link types, see Link types below.
            linkType: "direct", // or "direct"

            // Optional. A value of false (default) limits selection to a single file, while
            // true enables multiple file selection.
            multiselect: true, // or true

            // Optional. This is a list of file extensions. If specified, the user will
            // only be able to select files with these extensions. You may also specify
            // file types, such as "video" or "images" in the list. For more information,
            // see File types below. By default, all extensions are allowed.
            //extensions: ['.pdf', '.doc', '.docx'],

            // Optional. A value of false (default) limits selection to files,
            // while true allows the user to select both folders and files.
            // You cannot specify `linkType: "direct"` when using `folderselect: true`.
            folderselect: false, // or true

            // Optional. A limit on the size of each file that may be selected, in bytes.
            // If specified, the user will only be able to select files with size
            // less than or equal to this limit.
            // For the purposes of this option, folders have size zero.
            //sizeLimit: 1024, // or any positive number
        };
        $('#openDropbox').on('click', function () {
            Dropbox.choose(options);
        });

        //===================End of Dropbox=======================


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
            $('#yaControl').removeClass('d-none');
            if (yt !== '') {
                getYandexFiles(yt);
            } else {
                window.open('https://oauth.yandex.ru/authorize?response_type=token&client_id=e2e6c4743f6c470493e48748a09b2c3c&display=popup', '', 'width=600,height=800,toolbar=no,menubar=no');
            }
        });

        $('#yaDiskModal').on('hide.bs.modal', function () {
            $('#yaDiskContent').empty();
            yaPage = 1;
            $('#yaPrev').attr('disabled', true);
            $('#yaNext').attr('disabled', false);
            $('#yaControl').addClass('d-none');
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
            addFileToList($(fileToAdd).data('file-name'), $(fileToAdd).data('link'), 'yandex-disk', 'fab fa-yandex');
        });
        $('#yaDiskModal').modal('hide');
    });

    $('#yaNext').on('click', function () {
        yaPage++;
        getYandexFiles(yt);
        $('#addYandexFiles').attr('disabled', true);

    });
    $('#yaPrev').on('click', function () {
        if (yaPage > 1) {
            yaPage--;
            getYandexFiles(yt);
            $('#addYandexFiles').attr('disabled', true);
        }
        $('#yaNext').attr('disabled', false)
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
                limit: yaLimit,
                offset: (yaPage - 1) * yaLimit,
            },
            success: function (response) {
                if (response.items.length === 0) {
                    if (yaPage > 1) {
                        yaPage--;
                    }
                    $('#yaNext').attr('disabled', true);
                    console.log('No more files found');
                } else {
                    if (yaPage > 1) {
                        $('#yaPrev').attr('disabled', false);
                    } else {
                        $('#yaPrev').attr('disabled', true);
                    }
                    $('#yaDiskContent').empty();
                    $('#yaPageNumber').text(yaPage);
                    $(response.items).each(function (i, el) {
                        $('#yaDiskContent').append('<div class="ya-file" data-link="' + el.path + '" data-file-name="' + el.name + '">' + el.name + '</div>');
                    })
                }
            },
        });
    }
    function addFileToList(name, link, source, icon) {
            $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "'>" +
                "<i class='fas fa-paperclip mr-1'></i> <i class='" + icon + " mr-1'></i>" + name +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
    }
</script>