<div class="container footer">
    <div class="row">
        <div class="col-sm-12">
            <small class="text-secondary float-left">LUSY.IO BETA 1.0</small>
            <?php if (!is_null($id)): ?>
            <a href="#" id="showFeedbackForm" class="small text-secondary float-right">Обратная связь</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if (!is_null($id)): ?>
<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Обратная связь</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group" id="causeGroup">
                <input type="radio" name="cause" id="feedbackIdea" class="form-control-sm" value="idea">
                <label for="feedbackIdea">Предложение</label>
                    <input type="radio" name="cause" id="feedbackProblem" class="form-control-sm" value="problem">
                <label for="feedbackProblem">Проблема</label>
                    <input type="radio" name="cause" id="feedbackReview" class="form-control-sm" value="review">
                <label for="feedbackReview">Отзыв</label>
                </div>

                <input type="text" class="form-control mb-3" placeholder="Тема обращения" id="feedbackTitle">
                <textarea class="form-control" placeholder="Ваше сообщение*" id="feedbackMessage"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" id="sendFeedback" class="btn btn-primary">Отправить</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#showFeedbackForm').on('click', function () {
            $('#feedbackModal').modal('show');
        });

        $('input[name="cause"]').on('click', function () {

        });

        $('#feedbackMessage').on('keyup', function () {

        });

        $('#sendFeedback').on('click', function () {
            var fd = new FormData();
            var title = $('#feedbackTitle').val();
            var message = $('#feedbackMessage').val().trim();
            var cause = $('input[name="cause"]:checked').val();
            if (!cause) {
            }
            if ( message !== '') {
            }
            fd.append('module', 'addFeedback');
            fd.append('ajax', 'feedback');
            fd.append('cause', cause);
            fd.append('title', title);
            fd.append('message', message);
            if (cause && message !== '') {
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function () {
                        $('#feedbackTitle').val('');
                        $('#feedbackMessage').val('');
                        $('#feedbackModal').modal('hide');
                    },
                });
            }
        })
    })
</script>
<?php endif; ?>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/common.js"></script>
</body>
</html>