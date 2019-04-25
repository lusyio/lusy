

<div class="notyfeed">
    <div class="tab-content bg-white p-3" id="nav-tabContent">
        <div class="tab-pane fade show active " id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

            <div class="row">
                <div class="col-sm-12 comin">
                    <input class="form-control" id="comin" name="comment" type="text" autocomplete="off" placeholder="<?=$GLOBALS["_writecomment"]?>..." required>
                    <button type="submit" id="comment" class="btn btn-primary" title="<?=$GLOBALS['_send']?>"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>

            <div class="block-checkbox"  id="filters">
                <div class="center-checkbox col-sm-3">
                    <input rel="comment" type="checkbox" checked><p class="d-inline">Лента</p>
                </div>

                <div class="center-checkbox col-sm-3">
                    <input rel="report" type="checkbox"><p class="d-inline">Репорты</p>
                </div>

                <div class="center-checkbox col-sm-3">
                    <input rel="system" type="checkbox"><p class="d-inline">Система</p>
                </div>
            </div>


            <div id="comments"></div>
        </div>
    </div>
</div>
