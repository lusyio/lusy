<div class="container footer">
    <div class="row">
        <div class="col-sm-12">
            <a href="https://lusy.io/ru/history/" class="float-left small text-secondary" target="_blank">LUSY.IO
                v1.0.5</a>
            <?php if (!is_null($id)): ?>
                <a href="/mail/1/" class="small text-secondary float-right">Обратная связь</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="photo-preview-name m-0"></h5>
            </div>
            <img class="image-modal" src="">
            <div class="modal-footer" style="justify-content: flex-end">
                <span class="text-muted-new small d-none">
                    Дата загрузки : <span class="image-preview-date-upload">xx-xx-xxxx</span>
                </span>
                <span class="text-muted-new small">
                    Размер файла : <span class="image-preview-file-size">xx мб</span>
                    |
                    <a class="image-preview-open text-muted-new " target="_blank" href="">Открыть оригинал</a>
                </span>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/common.js?5"></script>
</body>
</html>
