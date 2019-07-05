<?php
$statusBar = [
    'new' => [
        'border' => 'border-success',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'inwork' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'overdue' => [
        'border' => 'border-danger',
        'bg' => 'badge-danger',
        'bg1' => 'bg-danger',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fab fa-gripfire',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'postpone' => [
        'border' => '',
        'bg' => 'badge-warning',
        'bg1' => 'bg-warning',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'far fa-clock',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'pending' => [
        'border' => 'border-warning',
        'bg' => 'badge-warning',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-warning',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'returned' => [
        'border' => 'border-primary',
        'bg' => 'badge-primary',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'done' => [
        'border' => 'border-success',
        'bg' => 'badge-success',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-success',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-check',
    ],
    'canceled' => [
        'border' => 'border-secondary',
        'bg' => 'badge-danger',
        'bg1' => 'bg-custom-color',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-danger',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-times',
    ],
    'planned' => [
        'border' => 'border-info',
        'bg' => 'badge-info',
        'bg1' => 'bg-warning',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-bolt',
        'ic2' => 'fas fa-eye',
        'ic3' => 'fas fa-times',
    ],
];
if ($dayost < 0) {
    $statusBar['postpone']['border'] = 'border-danger';
};
if ($view == 0) {
    $statusBar[$status]['border'] = 'border-secondary';
};
if ($id == $worker and $view == 0) {
    $statusBar[$status]['border'] = 'border-primary';
}
?>
<div id="task">
    <div class="card" style="margin-top: -21px;">
        <div class="card-body">
            <div class="row">
                <div class="col-4">
                    <span class="badge <?= $statusBar[$task['status']]['bg'] ?>"><?= $GLOBALS["_{$task['status']}"] ?></span>
                </div>
                <div class="col-8">
                    <div class="float-right" title="<?= $GLOBALS["_$status"] ?>">
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$task['status']]['bg1'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic1'] ?> custom"></i></span>
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$task['status']]['bg2'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic2'] ?> custom"></i></span>
                        <span class="status-icon-last rounded-circle noty-m <?= $statusBar[$task['status']]['bg3'] ?>"><i
                                    class="<?= $statusBar[$task['status']]['ic3'] ?> custom"></i></span>
                    </div>
                </div>
            </div>
            <h4 class="<?= $statusBar[$status]['border'] ?> font-weight-bold mb-3 mt-5"><?= $nametask ?></h4>
            <hr>
            <div class="row">
                <div class="col-6 col-lg-4">
                    <div class="position-relative deadline-block">
                        <div class="progress deadline-block-progress position-relative mr-1">
                            <div class="progress-bar bg-secondary-custom rounded" role="progressbar"
                                 style="width: <?= $dateProgress ?>%"
                                 aria-valuenow="<?= $dateProgress ?>%" aria-valuemin="0" aria-valuemax="100"></div>
                            <medium class="justify-content-center d-flex position-absolute w-100 h-100">
                                <div class="p-1 date-inside">
                                    <i class="far fa-calendar-times text-ligther-custom"></i><span
                                            class="text-ligther-custom ml-2 deadline-block-word"><?= $GLOBALS['_deadlinelist'] ?></span> <?= $dayDone ?> <?= $monthDone ?>
                                    <span></span>
                                </div>
                            </medium>
                        </div>
                        <span class="position-absolute edit"><i class="fas fa-pencil-alt"></i></span>
                        <div id="change-date">
                            <div class="form-group mb-0 p-3">
                                <?php if ($role != 'manager'): ?>
                                    <textarea name="report" id="reportarea1" class="form-control mb-2" rows="3"
                                              placeholder="Причина" required></textarea>
                                <?php endif; ?>
                                <input class="form-control form-control-sm mb-2" value="" type="date"
                                       id="deadlineInput"
                                       min="" required>
                                <button type="submit"
                                        id="<?= ($role == 'manager') ? 'sendDate' : 'sendpostpone'; ?>"
                                        class="btn btn-primary btn-sm float-left mb-3"><?= $GLOBALS["_change"] ?></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-8 ">
                    <div class="member-block">
                        <div class="float-right members-block">
                            <img src="/<?= getAvatarLink($manager) ?>" class="avatar mr-1">
                            <span class=" text-secondary slash">|</span>
                            <img src="/<?= getAvatarLink($worker) ?>" class="avatar ml-1">
                            <?php
                            $i = 1;
                            foreach ($coworkers as $coworker): ?>
                                <span class="mb-0"><img src="/<?= getAvatarLink($coworker['worker_id']) ?>"
                                                        alt="worker image" class="avatar ml-1"></span>
                                <?php
                                $i++;
                                if ($i == 2) break;
                            endforeach;

                            ?>
                        </div>
                        <?php
                        include __ROOT__ . '/engine/frontend/members/members.php';
                        ?>
                    </div>
                </div>
            </div>
            <div class="mt-5 mb-5 text-justify"><?= $description ?></div>
            <?php if (count($files) > 0): ?>
                <?php foreach ($files as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="text-secondary"><s><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></s>
                            (удален)</p>
                    <?php else: ?>
                        <p class="text-secondary"><a class="text-secondary" href="<?= ($file['file_size'] == 0) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            <div id="control">
                <?php
                include __ROOT__ . '/engine/backend/task/task/control/' . $role . '/' . $status . '.php';
                include __ROOT__ . '/engine/frontend/task/control/' . $role . '/' . $status . '.php';
                ?>
            </div>
        </div>
    </div>
    <?php if ($enableComments): ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex comin">
                <textarea class="form-control mr-2" id="comin" rows="1" name="comment" type="text" autocomplete="off"
                          placeholder="<?= $GLOBALS["_writecomment"] ?>..." required></textarea>

                    <?php $uploadModule = 'chat'; ?>
                    <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>

                    <button type="submit" id="comment" class="btn btn-primary" title="<?= $GLOBALS['_send'] ?>"><i
                                class="fas fa-paper-plane"></i></button>
                </div>
                <div style="display: none" class="bg-white file-name container-files">
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card mt-3">
        <div class="card-body">
            <?php include __ROOT__ . '/engine/frontend/task/notyfeed.php' ?>
        </div>
    </div>
</div>
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
        function addFileToList(name, link, source, icon) {
            $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "'>" +
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
    var $it = '<?=$idtask?>';
</script>
<script src="/assets/js/task.js?1"></script>
<script src="/assets/js/datepicker.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    (function (b) {
        b.fn.autoResize = function (f) {
            var a = b.extend({
                onResize: function () {
                }, animate: !0, animateDuration: 150, animateCallback: function () {
                }, extraSpace: 14, limit: 1E3
            }, f);
            this.filter("textarea").each(function () {
                var d = b(this).css({"overflow-y": "hidden", display: "block"}), f = d.height(), g = function () {
                    var c = {};
                    b.each(["height", "width", "lineHeight", "textDecoration", "letterSpacing"], function (b, a) {
                        c[a] = d.css(a)
                    });
                    return d.clone().removeAttr("id").removeAttr("name").css({
                        position: "absolute",
                        top: 0,
                        left: -9999
                    }).css(c).attr("tabIndex", "-1").insertBefore(d)
                }(), h = null, e = function () {
                    g.height(0).val(b(this).val()).scrollTop(1E4);
                    var c = Math.max(g.scrollTop(), f) + a.extraSpace, e = b(this).add(g);
                    h !== c && (h = c, c >= a.limit ? b(this).css("overflow-y", "") : (a.onResize.call(this), a.animate && "block" === d.css("display") ? e.stop().animate({height: c}, a.animateDuration, a.animateCallback) : e.height(c)))
                };
                d.unbind(".dynSiz").bind("keyup.dynSiz", e).bind("keydown.dynSiz", e).bind("change.dynSiz", e)
            });
            return this
        }
    })(jQuery);

    // инициализация
    jQuery(function () {
        jQuery('textarea').autoResize();
    });

    $(document).ready(function () {
        <?= ($worker == $id && $view == '0') ? 'decreaseTaskCounter();' : '' ?>
        $(document).on('click', function (e) { // событие клика по веб-документу
            var div = $(".deadline-block"); // тут указываем ID элемента
            var dov = $('#change-date');
            if (!div.is(e.target)  // если клик был не по нашему блоку
                && div.has(e.target).length === 0) { // и не по его дочерним элементам
                dov.fadeOut(200); // скрываем его
            }
        });

        <?php if ($tariff == 0):?>
        $('#openGoogleDrive, #openDropbox').attr('data-target', '#premModal');
        <?php endif; ?>

        // if (!$(e.target).closest(".tooltip-avatar").length) {
        //     $('.members').fadeOut(300);
        //     $('.coworkers').fadeOut(300);
        //     $('.responsible').fadeOut(300);
        // }
        // e.stopPropagation();
        // });

        $(".deadline-block").on('click', function () {
            $("#change-date").fadeIn(200);
        });
    });
</script>
