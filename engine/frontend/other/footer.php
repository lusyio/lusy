<div class="container footer">
    <div class="row">
        <div class="col-sm-12">
            <a href="https://lusy.io/ru/history/" class="float-left small text-secondary" target="_blank">LUSY.IO
                v1.0.4</a>
            <?php if (!is_null($id)): ?>
                <a href="/mail/1/" class="small text-secondary float-right">Обратная связь</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="rateModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-rate" role="document">
        <div class="modal-content border-0">
            <div class="modal-body pt-5 top-modal-body bg-white text-center position-relative">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h2>
                                Как вы оцениваете систему Lusy.io?
                            </h2>
                        </div>
                    </div>
                </div>
                <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
            </div>
            <div class="modal-body text-white pb-5 pt-5 bottom-modal-body position-relative">
                <div class="like-modal">
                    <img src="/upload/like-modal.png" alt="">
                </div>
                <div class="dislike-modal">
                    <img src="/upload/like-modal.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#rateModal').modal('show');
    });
</script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/common.js?3"></script>
</body>
</html>
