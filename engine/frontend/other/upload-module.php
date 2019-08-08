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
            <a class="dropdown-item attach-file" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
                <span>С устройства</span></a>
            <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        </div>
        <div class="modal fade limit-modal premModal" id="premModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header border-0 text-left d-block">
                        <h5 class="modal-title" id="exampleModalLabel">Похоже, вы исчерпали лимит загрузкок файлов через облачные хранилища</h5>
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
    <?php elseif ($uploadModule == 'task'): ?>
        <button class="text-muted btn btn-light btn-file border dropdown-toggle" type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    class="icon-paperclip fas fa-paperclip"></i>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item attach-file-review" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
                <span>С устройства</span></a>
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
            <a class="dropdown-item attach-file" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
                <span>С устройства</span></a>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 text-left d-block">
                <h5 class="modal-title" id="exampleModalLabel">Ого, вы израсходовали весь объем файлового хранилища</h5>
            </div>
            <div class="modal-body text-center position-relative">
                <div class="text-left text-block">
                    <p class="text-muted-new">Пора двигаться к новым горизонтам! Переходи на Premium тариф</p>
                    <p class="text-muted-new">Расширь свое хранилище до 1Гб и получи интеграцию с Google Drive и DropBox</p>
                </div>
                <span class="position-absolute">
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