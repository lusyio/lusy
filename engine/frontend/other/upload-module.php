<?php if ($uploadModule == 'task'): ?>
    <span class="btn btn-light btn-file border d-none">
    <i class="fas fa-file-upload custom-date mr-2"></i>
    <span class="attach-file text-muted"><?= $GLOBALS['_choosefilenewtask'] ?></span>
    <input id="sendFilesReview" type="file" multiple>
</span>
<?php
else:
?>
<span class="btn btn-light btn-file border d-none">
    <i class="fas fa-file-upload custom-date mr-2"></i>
    <span class="attach-file text-muted"><?= $GLOBALS['_choosefilenewtask'] ?></span>
    <input id="sendFiles" type="file" multiple>
</span>
<?php
endif;
?>
<div class="dropdown" empty-space="<?= $emptySpace ?>">
    <?php if ($uploadModule == 'chat'): ?>
        <button class="text-muted mr-2 btn btn-light btn-file border dropdown-toggle" type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    class="icon-paperclip fas fa-paperclip"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item attach-file" href="#"><i class="fas fa-file-upload custom-date mr-2"
                                                             style="width: 25px;text-align: center;"></i>
                <span>С компьютера</span></a>
            <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        </div>
        <div class="modal fade limit-modal premModal" id="premModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 600px;">
                <div class="modal-content">
                    <div class="modal-header border-0 text-left d-block">
                        <h5 class="modal-title" id="exampleModalLabel">Облачные хранилища</h5>
                    </div>
                    <div class="modal-body text-center position-relative">
                        <div class="text-left text-block">
                            <p class="text-blue">Извините, Вы исчерпали лимит загрузкок файлов через облачные хранилища.</p>
                            <p class="text-muted-new">Извините, Вы исчерпали лимит загрузкок файлов через облачные хранилища</p>
                        </div>
                        <span class="position-absolute" style="right:0;top: 0;">
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
    <?php elseif ($uploadModule == 'task'): ?>
        <button class="text-muted btn btn-light btn-file border dropdown-toggle" type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    class="icon-paperclip fas fa-paperclip"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item attach-file-review" href="#"><i class="fas fa-file-upload custom-date mr-2"
                                                             style="width: 25px;text-align: center;"></i>
                <span>С компьютера</span></a>
            <a class="dropdown-item" id="openGoogleDriveReview" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropboxReview" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        </div>
    <?php else: ?>
        <button class="btn btn-light btn-file dropdown-toggle btn-file-tasknew " type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="icon-paperclip-newtask fas fa-paperclip"></i>
            Прикрепить
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item attach-file" href="#"><i class="fas fa-file-upload custom-date mr-2"
                                                             style="width: 25px;text-align: center;"></i>
                <span>С компьютера</span></a>
            <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade limit-modal" id="fileSizeLimitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header border-0 text-left d-block">
                <h5 class="modal-title" id="exampleModalLabel">Недостаточно места в хранилище</h5>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="text-left text-block">
                    <p class="text-blue">Извините, место в хранилище ващей компании заканчивается, увеличить объем можно в Premium версии.</p>
                    <p class="text-muted-new">Извините, место в хранилище ващей компании заканчивается, увеличить объем можно в Premium версии</p>
                </div>
                <span class="position-absolute" style="right:0;top: 0;">
                <i class="fas fa-hdd icon-limit-modal"></i>
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