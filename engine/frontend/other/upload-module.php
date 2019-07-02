<span class="btn btn-light btn-file border d-none">
            <i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file text-muted"><?= $GLOBALS['_choosefilenewtask'] ?></span>
            <input id="sendFiles" type="file" multiple>
        </span>
<div class="dropdown">
    <button class="btn btn-light btn-file border dropdown-toggle" type="button" id="dropdownMenuButton"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Прикрепить файл</button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#"><i class="fas fa-file-upload custom-date mr-2"></i>
            <span class="attach-file">С компьютера</span></a>
        <a class="dropdown-item" id="openGoogleDrive" href="#" data-toggle="modal"><i
                    class="custom-date mr-2 fab fa-google-drive"></i>
            <span>Из Google Drive</span></a>
        <a class="dropdown-item" id="openDropbox" href="#" data-toggle="modal"><i
                    class="custom-date mr-2 fab fa-dropbox"></i>
            <span>Из Dropbox</span></a>
    </div>
</div>
