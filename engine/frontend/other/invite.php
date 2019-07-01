<form id="create-invite" method="post" action="">
    <div class="card mb-3">
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-md-9 col-sm-6">
                    <div class="input-group mb-1">
                        <input id="invitee-mail" class="form-control" type="email" name="invitee-mail"
                               aria-describedby="emailHelp"
                               placeholder="<?= $GLOBALS['_placeholderinvite'] ?>"
                               required>
                    </div>
                    <small class="text-ligther"><?= $GLOBALS['_howtoinvite'] ?></small>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button type="submit" class="btn btn-primary rounded w-100">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        <?= $GLOBALS['_createinvite'] ?></button>
                </div>
                <span class="position-absolute small text-muted-reg text-center mt-2" style="right: 14px; display: none; top: 57px;"><?= $GLOBALS['_usedemailinvite'] ?></span>
            </div>
        </div>
    </div>
</form>
<hr>
<div class="invite-container">

        <div class="d-sm task-box mb-1 invites-header d-none">
            <div class="card-body pb-2  ">
                <div class="row sort">
                    <div class="col-4">
                        <span><?= $GLOBALS['_email'] ?></span>
                    </div>
                    <div class="col-4">
                        <span><?= $GLOBALS['_dateinvite'] ?></span>
                    </div>
                    <div class="col-4">
                        <span><?= $GLOBALS['_statustasks'] ?></span>
                    </div>
                </div>
            </div>
        </div>

    <div id="invites-container">
        <?php include 'engine/ajax/invite-card.php' ?>
    </div>

</div>
<script>
    $(document).ready(function () {
        updateHeader();
        function updateHeader() {
            if ($('.invite-card:visible').length != 0){
                $('.invites-header').removeClass('d-none');
            } else{
                $('.invites-header').addClass('d-none');
            }
        }

        updateInvites();

        // функция загрузки ивайтов
        function updateInvites() {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                data: {
                    ajax: 'invite-card'
                },
                success: onInviteSuccess,
            });

            function onInviteSuccess(data) {
                $('#invites-container').html(data).fadeIn();
            }

        }

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $('.invite-container').on('click', '.invite-cancel', function (e) {
            e.preventDefault();
            var el = $(this);
            var inviteId = el.data('invite-id');
            var fd = new FormData();
            fd.append('ajax', 'invite');
            fd.append('module', 'deleteInvite');
            fd.append('inviteId', inviteId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    $.when(el.parents('.invite-card').fadeOut(300)).done(function () {
                        updateHeader();
                    });
                    console.log(data);
                }
            });
        });

        $('#create-invite').on('submit', function (e) {
            e.preventDefault();
            var inviteeMail = $('#invitee-mail').val();
            if (inviteeMail) {
                $('.invite-send').prop('disabled', true);
                $('.spinner-border').removeClass('d-none');
                var fd = new FormData();
                fd.append('ajax', 'invite');
                fd.append('module', 'createInvite');
                fd.append('invitee-mail', inviteeMail);
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',

                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        console.log(data);
                        if (data) {
                            $('.invite-send').prop('disabled', false);
                            $('.spinner-border').addClass('d-none');
                            if (data === 'emailexist') {
                                $('#invitee-mail').css({
                                    'border-color': '#dc3545',
                                    'transition': '1000ms'
                                });
                                setTimeout(function () {
                                    $('#invitee-mail').css('border-color', "#ced4da");
                                }, 1000);
                                $.when($(".text-muted-reg").fadeIn(300)).done($(".text-muted-reg").fadeOut(1000));
                            } else {
                                $(".text-muted-reg").addClass('d-none');
                                $('.invites-header').removeClass('d-none');
                                updateInvites();
                            }
                        } else {
                            console.log('Произошла ошибка');
                        }
                    },
                });
            }
        });
    });
</script>
