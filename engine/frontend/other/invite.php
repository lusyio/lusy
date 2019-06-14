<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
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
                    <div class="text-muted-reg text-center mt-3 d-none"><?= $GLOBALS['_usedemailinvite'] ?></div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <button type="submit" class="btn btn-primary rounded w-100">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        <?= $GLOBALS['_createinvite'] ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<hr>
<div class="invite-container">
    <?php if (count($invites)): ?>
        <div class="d-sm task-box mb-1">
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
    <?php
    endif;
    ?>
</div>
<script>
    $(document).ready(function () {

        // updateInvites();

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

        // $(".invite-container").on('click', '.copy-link', function () {
        //     var val = $(this).attr('val');
        //     var textArea = document.createElement("textarea");
        //     textArea.value = val;
        //     document.body.appendChild(textArea);
        //     textArea.select();
        //     document.execCommand('copy');
        //     document.body.removeChild(textArea);
        // });

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
                    el.parents('.invite-card').fadeOut(300);
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
                                $(".text-muted-reg").removeClass('d-none');
                            } else {
                                $(".text-muted-reg").addClass('d-none');
                                updateInvites();
                                // $('#invitee-mail').val('');
                                // var invite = JSON.parse(data);
                                // var inviteRow = "";
                                // $('#invites-container').load("/invite/ #invite-card");
                                // inviteRow += window.location.hostname.toString() + '/join/' + invite['code'] + '/';
                                // $('.invite-container').append("<div class=\"card mb-2 invite-card\">\n" +
                                //     "                <div class=\"card-body p-2 text-center\">\n" +
                                //     "                    <div class=\"row\">\n" +
                                //     "                        <div class=\"col-1\">\n" +
                                //     "                            <span><i val=\' " + inviteRow + " \'\n" +
                                //     "                                     class=\"far fa-copy copy-link\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Скопировать приглашение\"></i></span>\n" +
                                //     "                        </div>\n" +
                                //     "                        <div class=\"col-4\">\n" + invite['email'] +
                                //     "                        </div>\n" +
                                //     "                        <div class=\"col-4\">\n" +
                                //     "                            <div class=\"invite-date\">\n" + invite['invite_date'] +
                                //     "                            </div>\n" +
                                //     "                        </div>\n" +
                                //     "                            <div class=\"col-3\">\n" +
                                //     "                                <span>Отправлено</span>\n" +
                                //     "                                <a class=\"invite-cancel\" data-invite-id=\' " + invite.invite_id + " \'><i\n" +
                                //     "                                            class=\"far fa-times-circle invite-delete\"></i></a>\n" +
                                //     "                            </div>\n" +
                                //     "                    </div>\n" +
                                //     "                </div>\n" +
                                //     "            </div>");
                                // console.log(inviteRow);
                                // var $newInvite = $('.invite-card').first();
                                // $newInvite.css({'background-color': '#dbe7f6'});
                                // $('html, body').animate({
                                //     scrollTop: $newInvite.offset().top - 100
                                // }, 1000);
                                // setTimeout(function () {
                                //     $newInvite.attr('style', '');
                                // }, 3000);
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
