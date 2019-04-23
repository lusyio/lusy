

<div class="notyfeed">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Лента (1)</a>
<!--            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Файлы (0)</a>-->
<!--            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Информация</a>-->
        </div>
    </nav>

    <div class="tab-content bg-white p-3" id="nav-tabContent">
        <div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row">
                <div class="col-sm-12 comin">
                    <input class="form-control" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
                    <button type="submit" id="comment" class="btn btn-primary" title="<?=$GLOBALS['_send']?>"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
            <div id="comments"></div>
        </div>
    </div>
</div>