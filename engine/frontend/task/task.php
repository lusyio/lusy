<?php
$textColor = [
    'new' => 'text-primary',
    'inwork' => 'text-primary',
    'overdue' => 'text-danger',
    'postpone' => 'text-warning',
    'pending' => 'text-warning',
    'returned' => 'text-primary',
    'done' => 'text-success',
    'canceled' => 'text-secondary',
    'planned' => 'text-info',
];
$taskStatusText = [
        'new' => $GLOBALS['_inprogresslist'],
        'inwork' => $GLOBALS['_inprogresslist'],
        'overdue' => $GLOBALS['_overduelist'],
        'postpone' => $GLOBALS['_postponelist'],
        'pending' => $GLOBALS['_pendinglist'],
        'returned' => $GLOBALS['_returnedlist'],
        'done' => $GLOBALS['_donelist'],
        'canceled' => $GLOBALS['_canceledlist'],
        'planned' => $GLOBALS['_plannedlist'],
];
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
        'bg1' => 'bg-primary',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-exchange-alt',
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
        'bg1' => 'bg-info',
        'bg2' => 'bg-custom-color',
        'bg3' => 'bg-custom-color',
        'ic1' => 'fas fa-pause',
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
    <div class="card mt-3 task-container-dragover">
        <div class="card-body <?= ((count($subTasks) > 0)) ? 'shadow-subtask' : ''; ?>">
            <div class="row">
                <div class="col-12 subtask-badge-mobile">
                    <?php if (!is_null($parentTaskId)): ?>
                        <a href="/task/<?= $parentTaskId ?>/"><span data-toggle="tooltip" data-placement="bottom"
                                                                           title="Перейти к надзадаче"
                                                                           class="badge badge-info"><i class="fas fa-clipboard mr-2"></i><?= $parentTaskName ?></span></a>
                    <?php endif; ?>
                </div>
                <div class="col-8 col-lg-8">
                    <span class="badge <?= $statusBar[$status]['bg'] ?>"><?= $GLOBALS["_{$status}"] ?></span>
                    <?php if (!is_null($parentTaskId)): ?>
                        <a class="subtask-badge-desktop" href="/task/<?= $parentTaskId ?>/"><span data-toggle="tooltip" data-placement="bottom"
                                                                           title="Перейти к надзадаче"
                                                                           class="badge badge-info"><i class="fas fa-clipboard mr-2"></i><?= $parentTaskName ?></span></a>
                    <?php endif; ?>
                </div>
                <div class="col-4 col-lg-4">
                    <div class="float-right" title="<?= $GLOBALS["_$status"] ?>">
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$status]['bg1'] ?>"><i
                                    class="<?= $statusBar[$status]['ic1'] ?> custom"></i></span>
                        <span class="status-icon rounded-circle noty-m <?= $statusBar[$status]['bg2'] ?>"><i
                                    class="<?= $statusBar[$status]['ic2'] ?> custom"></i></span>
                        <span class="status-icon-last rounded-circle noty-m <?= $statusBar[$status]['bg3'] ?>"><i
                                    class="<?= $statusBar[$status]['ic3'] ?> custom"></i></span>
                    </div>
                </div>
            </div>
            <h4 class="<?= $statusBar[$status]['border'] ?> font-weight-bold mb-3 mt-5">
                <?= $nametask ?>
                <?php if ($enableEdit): ?>
                    <?php if ($tariff == 1 || $tryPremiumLimits['edit'] < 3): ?>
                        <?php if ($tariff == 0): ?>
                            <a class="float-right" data-toggle="tooltip" data-placement="bottom" title="Редактировать задачу (Осталось использований в бесплатном тарифе <?= 3 - $tryPremiumLimits['edit'] ?>/3)" href="./edit/"><i
                                        class="fas fa-pencil-alt edit-profile"></i></a>
                        <?php else: ?>
                            <a class="float-right" data-toggle="tooltip" data-placement="bottom" title="Редактировать задачу" href="./edit/"><i
                                        class="fas fa-pencil-alt edit-profile"></i></a>
                        <?php endif; ?>
                    <?php else: ?>
                            <a class="float-right" data-toggle="tooltip" data-placement="bottom" title="Редактировать задачу" href="#"><i
                                        id="editTask" class="fas fa-pencil-alt edit-profile"></i></a>
                    <?php endif; ?>
                <?php endif; ?>
            </h4>

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
                        <?php if (($isCeo || !$isCoworker) && !in_array($status, ['done', 'canceled'])): ?>
                            <span class="position-absolute edit"><i class="fas fa-pencil-alt"></i></span>
                            <div id="change-date">
                                <div class="form-group mb-0 p-3">
                                    <?php if ($role != 'manager'): ?>
                                        <textarea name="report" id="reportarea1" class="form-control mb-2" rows="3"
                                                  placeholder="Причина" required></textarea>
                                    <?php endif; ?>
                                    <input class="form-control form-control-sm mb-2" value="<?= ($status == 'planned')? date('Y-m-d', $datecreateSeconds) : date("Y-m-d", $actualDeadline); ?>" type="date"
                                           id="deadlineInput"
                                           datedone="<?= date("Y-m-d", $actualDeadline) ?>"
                                           min="<?= ($status == 'planned')? date('Y-m-d', $datecreateSeconds) : $GLOBALS["now"] ?>" required>
                                    <button type="submit"
                                            id="<?= ($role == 'manager') ? 'sendDate' : 'sendpostpone'; ?>"
                                            class="btn btn-primary btn-sm float-left mb-3"><?= $GLOBALS["_change"] ?></button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-6 col-lg-8 ">
                    <div class="member-block">
                        <div class="float-right members-block">
                            <img src="/<?= getAvatarLink($manager) ?>" class="avatar">
                            <?php if ($manager != $worker): ?>
                                <span class="ml-1 mr-1 text-secondary slash">|</span>
                                <img src="/<?= getAvatarLink($worker) ?>" class="avatar">
                            <?php endif; ?>
                        </div>
                        <?php
                        include __ROOT__ . '/engine/frontend/members/members.php';
                        ?>
                    </div>
                </div>
            </div>
            <div class="mt-5 mb-5 text-justify"><?= $description ?></div>
            <?php if ($checklist != []): ?>
                <div class="collapse-checklist">
                    <input type="hidden" id="fullUserName" value="<?= getDisplayUserName($id) ?>">
                    <?php
                    foreach ($checklist as $k => $n):
                        ?>
                        <label class="pure-material-checkbox d-block">
                            <input type="checkbox" class="checkbox-checklist" idChecklist="<?= $k ?>" <?= ($n['status'] == 1)? 'checked': '' ?> <?= (($role != 'manager' && $n['status'] == 1 && ($n['checkedBy'] != $id || $n['checkTime'] < time() - 300 )) || ($status == 'done' || $status == 'canceled')) ? 'disabled': '' ?>>
                            <span class=""> <span class="text-checklist"><?= $n['text'] ?></span> <span class="small text-muted-new"><?= ($n['status'] == 1)? ' (' . $n['name'] . ')' :'' ?></span></span>
                        </label>
                    <?php
                    endforeach;
                    ?>
                </div>
            <?php endif; ?>
            <?php if (count($files) > 0): ?>
            <div class="d-flex flex-wrap">
                <?php foreach ($files as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="text-secondary"><s><i class="fas fa-paperclip"></i> <?= $file['file_name'] ?></s>
                            (удален)</p>
                    <?php else: ?>
                    <?php if (in_array($file['extension'],['png', 'jpeg', 'jpg', 'bmp'])): ?>
                    <div class="photo-preview-container-task-hover mr-2">
                        <div data-target=".bd-example-modal-xl" data-toggle="modal" class="text-secondary photo-preview-container mb-4 photo-preview-container-task clear_fix">
                            <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview" target="_blank" style="pointer-events: none;background-image: url('/<?= $file['file_path']; ?>')"
                                                     href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                        <p class="small text-muted-new text-center photo-preview-area-message m-0">
                            <?= $file['file_name'] ?>
                        </p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="photo-preview-container-task-hover mr-2">
                        <div class="text-secondary photo-preview-container mb-4 photo-preview-container-task clear_fix">
                            <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview" target="_blank" style="background-size: contain;background-image: url('/upload/file.png')"
                               href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                            <p class="small text-muted-new text-center photo-preview-area-message m-0">
                                <?= $file['file_name'] ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
            <?php if ($isCeo || (!$isCoworker && ($worker == $id || $manager == $id))): ?>
                <div id="control">
                    <?php
                    include __ROOT__ . '/engine/backend/task/task/control/' . $role . '/' . $status . '.php';
                    include __ROOT__ . '/engine/frontend/task/control/' . $role . '/' . $status . '.php';
                    ?>
                </div>
            <?php endif; ?>

        </div>
        <?php if (count($subTasks) > 0): ?>
            <div class="subTaskInList subtask-task">
                <?php foreach ($subTasks as $subTask): ?>
                    <a class="text-decoration-none cust<?= (in_array($subTask->get('id'), $unfinishedSubTasks)) ? ' not-finished': '';?>" idtask="<?= $subTask->get('id') ?>" href="/task/<?= $subTask->get('id') ?>/">
                        <div class="card-footer border-0 card-footer-subtask">
                            <div class="d-block">
                                <div class="row">
                                    <div class="col-sm-5 col-lg-5 col-md-12 col-12">
                                        <div class="text-area-message">
                                            <span class="taskname taskname-subtask-task"><span class="<?= $textColor[$subTask->get('status')] ?> pr-1">—</span> <?= $subTask->get('name'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-1 pl-0">
                                        <div class="d-flex fc">
                                            <div class="informer d-flex mr-3"><i class="fas fa-comments">
                                                </i><span class="ml-1"><?= $subTask->get('countComments') ?></span>
                                                <span class="ml-1 text-primary"><?= ($subTask->get('countNewComments') > 0) ? '+' . $subTask->get('countNewComments') : '' ?></span>
                                            </div>
                                            <div class="informer d-flex">
                                                <i class="fas fa-file"></i><span class="ml-1"><?= $subTask->get('countAttachedFiles') ?></span>
                                                <span class="ml-1 text-primary"><?= ($subTask->get('countNewFiles') > 0) ? '+' . $subTask->get('countNewFiles') : '' ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-5 col-5 pr-0">
                                        <span class="subtask-status"><?= $taskStatusText[$subTask->get('status')] ?></span>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-3 subtask-status col-3 <?= ($subTask->get('status') == 'overdue') ? 'text-danger font-weight-bold' : ''; ?> <?= (in_array($subTask->get('status'), ['inwork', 'new', 'returned']) && date("Y-m-d", $subTask->get('datedone')) == $GLOBALS["now"]) ? 'text-warning font-weight-bold' : ''; ?>">
                                            <?= date('j', $subTask->get('datedone')) ?> <?= $_months[date('n', $subTask->get('datedone')) - 1] ?>
                                    </div>
                                    <div class="col-sm-2 col-lg-2 col-md-4 col-4 avatars">
                                        <div class="avatar-subtask-task">
                                            <img src="/<?= getAvatarLink($subTask->get('manager')) ?>" class="avatar"> |
                                            <img src="/<?= getAvatarLink($subTask->get('worker')) ?>" class="avatar">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="comment-container-task">
    <?php if ($enableComments): ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex comin">
                <textarea class="form-control mr-2" id="comin" rows="1" name="comment" type="text"data-toggle="tooltip" data-placement="bottom"
                          title="Введите сообщение" autocomplete="off"
                          placeholder="<?= $GLOBALS["_writecomment"] ?>..."></textarea>

                    <?php $uploadModule = 'chat'; ?>
                    <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>

                    <button type="submit" id="comment" class="btn btn-primary" title="<?= $GLOBALS['_send'] ?>"><i
                                class="fas fa-paper-plane"></i></button>
                </div>
                <div class="bg-white file-name container-files display-none">
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
<div class="modal fade limit-modal" id="disabledEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-left d-block">
                <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит редактирования задач в бесплатном тарифе</h5>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="text-left text-block">
                    <p class="text-muted-new">Все ошибаются, но не все исправляют ошибки</p>
                    <p class="text-muted-new">Переходи на Premium тариф, и получи безграничные возможности редактирования созданных задач</p>
                </div>
                <span class="position-absolute">
                <i class="fas fa-chart-pie icon-limit-modal"></i>
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
<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="photo-preview-name m-0"></h5>
            </div>
            <img class="image-modal" src="">
            <div class="modal-footer" style="justify-content: space-between">
                <span class="text-muted-new small d-none">
                    Дата загрузки : <span class="image-preview-date-upload">xx-xx-xxxx</span>
                </span>
                <span class="text-muted-new small">
                    Размер файла : <span class="image-preview-file-size">xx мб</span>
                    |
                    <a class="image-preview-open text-muted-new " href="">Открыть оригинал</a>
                </span>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>
<?php if ($tariff == 1 || $tryPremiumLimits['cloud'] < 3): ?>
    <script type="text/javascript">
        $('#openGoogleDrive').click(function () {
            $(this).data('clicked', true);
            $('#openGoogleDriveReview').data('clicked', false);
        });
        $('#openGoogleDriveReview').click(function () {
            $(this).data('clicked', true);
            $('#openGoogleDrive').data('clicked', false);
        });
        //=======================Google Drive==========================
        //=Create object of FilePicker Constructor function function & set Properties===
        function SetPicker() {
            var Options = {
                apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                buttonEl: document.getElementById("openGoogleDrive"),
                onClick: function (file) {
                }
            };
            var Options2 = {
                apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                buttonEl: document.getElementById("openGoogleDriveReview"),
                onClick: function (file) {
                }
            };
            var picker = new FilePicker(Options);
            var picker2 = new FilePicker(Options2);
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
                        if ($('#openGoogleDrive').data('clicked')) {
                            addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive', 'file-name');
                        }
                        if ($('#openGoogleDriveReview').data('clicked')) {
                            $('#openGoogleDrive').data('clicked', false);
                            addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive', 'file-name-review');
                        }
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

        $('#openDropbox').on('click', function () {
            options = {
                success: function (files) {
                    files.forEach(function (file) {
                        addFileToList(file.name, file.link, file.bytes, 'dropbox', 'fab fa-dropbox', 'file-name');
                    })
                },
                linkType: "direct", // or "preview"
                multiselect: true, // or false
                folderselect: false, // or true
            };
            Dropbox.choose(options);
        });
        $('#openDropboxReview').on('click', function () {
            options = {
                success: function (files) {
                    files.forEach(function (file) {
                        addFileToList(file.name, file.link, file.bytes, 'dropbox', 'fab fa-dropbox', 'file-name-review');
                    })
                },
                linkType: "direct", // or "preview"
                multiselect: true, // or false
                folderselect: false, // or true
            };
            Dropbox.choose(options);
        });

        //===================End of Dropbox=======================
        function addFileToList(name, link, size, source, icon, cont) {
            console.log(cont);
            $('.' + cont).show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "' data-file-size='" + size + "'>" +
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

        <?php if ($tariff == 0 && $tryPremiumLimits['cloud'] >= 3):?>
        $('#openGoogleDrive, #openDropbox, #openDropboxReview, #openGoogleDriveReview').click(function () {
            $('.premModal').modal('show');
        });
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

        $('#editTask').on('click', function (e) {
            e.preventDefault();
            $('#disabledEditModal').modal('show');
        });
    });
</script>
