<span class="btn btn-light btn-file border d-none">
            <i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file text-muted"><?= $GLOBALS['_choosefilenewtask'] ?></span>
            <input id="sendFiles" type="file" multiple>
        </span>
<div class="dropdown">
    <?php if ($uploadModule == 'chat'): ?>
        <button class="text-muted mr-2 btn btn-light btn-file border dropdown-toggle" type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-paperclip"
                                                                                     style="font-size: 20px;vertical-align: middle;"></i>
        </button>
    <?php else: ?>
        <button class="text-muted btn btn-light btn-file border dropdown-toggle" type="button" id="dropdownMenuButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Прикрепить файл
        </button>
    <?php endif; ?>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file">С компьютера</span></a>
        <?php if ($tariff == 1): ?>
            <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        <?php else: ?>
            <a class="dropdown-item" id="openGoogleDrive" href="#" data-target="#premModal" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-google-drive"></i>
                <span>Из Google Drive</span></a>
            <a class="dropdown-item" id="openDropbox" data-target="#premModal" href="#" data-toggle="modal"><i
                        class="custom-date mr-2 fab fa-dropbox"></i>
                <span>Из Dropbox</span></a>
        <?php endif; ?>
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
                Извините, но функция загрузки файлов из облачных хранилищ доступна только в Premium версии. 😞
            </div>
            <div class="modal-footer border-0">
                <a href="/company-settings/" class="btn btn-primary">Перейти к тарифам</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>