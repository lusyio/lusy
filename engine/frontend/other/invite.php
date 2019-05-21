<!--<table class="table" id="invites-table">-->
<!--    --><?php //if (count($invites)): ?>
<!--    <thead>-->
<!--    <tr>-->
<!--        <th>Ссылка</th>-->
<!--        <th>Позиция</th>-->
<!--        <th>Дата отправки</th>-->
<!--        <th>Почта</th>-->
<!--        <th>Статус</th>-->
<!--        <th></th>-->
<!--    </tr>-->
<!--    </thead>-->
<!--    <tbody id="body-invites-table">-->
<!--    --><?php //foreach ($invites as $invite): ?>
<!--        <tr>-->
<!--            <td>--><? //= $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/'; ?><!--</td>-->
<!--            <td>--><? //= $invite['invitee_position'] ?><!--</td>-->
<!--            <td>--><? //= $invite['invite_date'] ?><!--</td>-->
<!--            <td>--><? //= $invite['email'] ?><!--</td>-->
<!--            --><?php //if ($invite['status']): ?>
<!--                <td><a href="/../profile/--><? //= $invite['status']; ?><!--">Зарегистрирован</a></td>-->
<!--                <td></td>-->
<!--            --><?php //else: ?>
<!--                <td>Отправлено</td>-->
<!--                <td><a href="#" class="invite-cancel" data-invite-id="--><? //= $invite['invite_id'] ?><!--">Отменить</a></td>-->
<!--            --><?php //endif; ?>
<!--        </tr>-->
<!--    --><?php
//    endforeach;
//    endif;
//    ?>
<!--    </tbody>-->
<!--</table>-->
<h3 class="pb-3"><b>Отправить приглашение</b></h3>
<form id="create-invite" method="post" action="">
    <div class="card mb-3">
        <div class="card-body">
            <div class="mb-3 text-center">
                <a class="d-inline float-left" href="/company/"><i class="fas fa-arrow-left icon-invite"></i></a>
                <div class="text-reg ml-5">
                    Чтобы пригласить сотрудника, введите его почту и укажите роль
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <input id="invitee-mail" class="form-control" type="email" name="invitee-mail"
                               aria-describedby="emailHelp"
                               placeholder="Почта получателя"
                               required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group text-center">
                        <select id="invitee-position" class="custom-select" name="position" required>
                            <option disabled selected style='display:none;'>Роль</option>
                            <option value="admin">Admin</option>
                            <option value="worker">Worker</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center mt-3">
                    <button type="submit" class="brn btn-outline-primary rounded invite-send">Отправить</button>
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
                                     class="far fa-copy copy-link"></i></span>
                        </div>
                        <div class="col-2">
                            <?= $invite['invitee_position'] ?>
                        </div>
                        <div class="col-4">
                            <?= $invite['email'] ?>
                        </div>
                        <div class="col-2">
                            <div class="invite-date">
                                <?= $invite['invite_date'] ?>
                            </div>
                        </div>
                        <?php if ($invite['status']): ?>
                            <div class="col">
                                <a href="/../profile/<?= $invite['status']; ?>">Зарегистрирован</a>
                            </div>
                        <?php else: ?>
                            <div class="col">
                                <span>Отправлено</span>
                                <a class="invite-cancel" data-invite-id="<?= $invite['invite_id'] ?>"><i
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

        $(".invite-container").on('click', '.copy-link', function () {
            var val = $(this).attr('val');
            var textArea = document.createElement("textarea");
            textArea.value = val;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
        });


        $('.invite-container').on('click', '.invite-cancel', function () {
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
            var inviteePosition = $('#invitee-position').val();
            if (inviteeMail && inviteePosition) {
                $('#btn-restore').prop('disabled', true);
                $('#spinner-restore').removeClass('d-none');

                var fd = new FormData();
                fd.append('ajax', 'invite');
                fd.append('module', 'createInvite');
                fd.append('invitee-mail', inviteeMail);
                fd.append('invitee-position', inviteePosition);
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
                            var invite = JSON.parse(data);
                            var inviteRow = "";
                            inviteRow += window.location.hostname.toString() + '/join/' + invite['code'] + '/';
                            $('.invite-container').append("<div class=\"card mb-2 invite-card\">\n" +
                                "                <div class=\"card-body p-2 text-center\">\n" +
                                "                    <div class=\"row\">\n" +
                                "                        <div class=\"col-1\">\n" +
                                "                            <span><i val=\' " + inviteRow + " \'\n" +
                                "                                     class=\"far fa-copy copy-link\"></i></span>\n" +
                                "                        </div>\n" +
                                "                        <div class=\"col-2\">\n" + invite['invitee_position'] +
                                "                        </div>\n" +
                                "                        <div class=\"col-4\">\n" + invite['email'] +
                                "                        </div>\n" +
                                "                        <div class=\"col-2\">\n" +
                                "                            <div class=\"invite-date\">\n" + invite['invite_date'] +
                                "                            </div>\n" +
                                "                        </div>\n" +
                                "                            <div class=\"col\">\n" +
                                "                                <span>Отправлено</span>\n" +
                                "                                <a class=\"invite-cancel\" data-invite-id=\' " + invite.invite_id + " \'><i\n" +
                                "                                            class=\"far fa-times-circle invite-delete\"></i></a>\n" +
                                "                            </div>\n" +
                                "                    </div>\n" +
                                "                </div>\n" +
                                "            </div>");
                            console.log(inviteRow);
                        } else {
                            console.log('Произошла ошибка');
                        }
                    },
                });
            }
        })
    })
</script>
