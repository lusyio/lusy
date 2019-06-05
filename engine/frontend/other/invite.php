<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<h3 class="pb-3"><b><?= $GLOBALS['_headerinvite'] ?></b></h3>
<form id="create-invite" method="post" action="">
    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3 text-center">
                <a class="d-inline float-left" href="/company/"><i class="fas fa-arrow-left icon-invite"></i></a>
                <div class="text-reg ml-4">
                    <?= $GLOBALS['_howtoinvite'] ?>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3">
                    <div class="input-group">
                        <input id="invitee-mail" class="form-control" type="email" name="invitee-mail"
                               aria-describedby="emailHelp"
                               placeholder="<?= $GLOBALS['_placeholderinvite'] ?>"
                               required>
                    </div>
                    <div class="text-muted-reg text-center mt-3 d-none"><?= $GLOBALS['_usedemailinvite'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center mt-3">
                    <button type="submit" class="btn btn-outline-primary rounded invite-send">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        <?= $GLOBALS['_createinvite'] ?></button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="invite-container">
    <?php if (count($invites)): ?>
        <?php foreach ($invites as $invite): ?>
            <div class="card mb-2 invite-card">
                <div class="card-body p-2 text-center">
                    <div class="row">
                        <div class="col-1">
                            <span><i val="<?= $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/'; ?>"
                                     class="far fa-copy copy-link" data-toggle="tooltip" data-placement="bottom" title="<?= $GLOBALS['_copyinvite'] ?>"></i></span>
                        </div>
                        <div class="col-4">
                            <?= $invite['email'] ?>
                        </div>
                        <div class="col-4">
                            <div class="invite-date">
                                <?= $invite['invite_date'] ?>
                            </div>
                        </div>
                        <?php if ($invite['status']): ?>
                            <div class="col-3">
                                <a href="/../profile/<?= $invite['status']; ?>"><?= $GLOBALS['_registeredinvite'] ?></a>
                            </div>
                        <?php else: ?>
                            <div class="col-3">
                                <span><?= $GLOBALS['_sentinvite'] ?></span>
                                <a href="#" class="invite-cancel" data-invite-id="<?= $invite['invite_id'] ?>"><i
                                            class="far fa-times-circle invite-delete"></i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
    ?>
</div>
<script>
    $(document).ready(function () {

        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });

        $(".invite-container").on('click', '.copy-link', function () {
            var val = $(this).attr('val');
            var textArea = document.createElement("textarea");
            textArea.value = val;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
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
                                $('#invitee-mail').val('');
                                var invite = JSON.parse(data);
                                var inviteRow = "";
                                inviteRow += window.location.hostname.toString() + '/join/' + invite['code'] + '/';
                                $('.invite-container').append("<div class=\"card mb-2 invite-card\">\n" +
                                    "                <div class=\"card-body p-2 text-center\">\n" +
                                    "                    <div class=\"row\">\n" +
                                    "                        <div class=\"col-1\">\n" +
                                    "                            <span><i val=\' " + inviteRow + " \'\n" +
                                    "                                     class=\"far fa-copy copy-link\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Скопировать приглашение\"></i></span>\n" +
                                    "                        </div>\n" +
                                    "                        <div class=\"col-4\">\n" + invite['email'] +
                                    "                        </div>\n" +
                                    "                        <div class=\"col-4\">\n" +
                                    "                            <div class=\"invite-date\">\n" + invite['invite_date'] +
                                    "                            </div>\n" +
                                    "                        </div>\n" +
                                    "                            <div class=\"col-3\">\n" +
                                    "                                <span>Отправлено</span>\n" +
                                    "                                <a class=\"invite-cancel\" data-invite-id=\' " + invite.invite_id + " \'><i\n" +
                                    "                                            class=\"far fa-times-circle invite-delete\"></i></a>\n" +
                                    "                            </div>\n" +
                                    "                    </div>\n" +
                                    "                </div>\n" +
                                    "            </div>");
                                console.log(inviteRow);
                                var $newInvite = $('.invite-card').last();
                                $newInvite.css({'background-color': '#dbe7f6'});
                                $('html, body').animate({
                                    scrollTop: $newInvite.offset().top - 100
                                }, 1000);
                                setTimeout(function () {
                                    $newInvite.attr('style', '');
                                }, 3000);
                            }
                        } else {
                            console.log('Произошла ошибка');
                        }
                    },
                });
            }
        })
    })
</script>
