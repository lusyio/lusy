<table class="table" id="invites-table">
    <?php if (count($invites)): ?>
    <thead>
    <tr>
        <th>Имя</th>
        <th>Ссылка</th>
        <th>Позиция</th>
        <th>Дата отправки</th>
        <th>Почта</th>
        <th>Статус</th>
        <th></th>
    </tr>
    </thead>
    <tbody id="body-invites-table">
    <?php foreach ($invites as $invite): ?>
        <tr>
            <td><?= $invite['invitee_name'] ?></td>
            <td><?= $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/'; ?></td>
            <td><?= $invite['invitee_position'] ?></td>
            <td><?= $invite['invite_date'] ?></td>
            <td><?= $invite['invitee_name'] ?></td>
            <td><?= $invite['status'] ?></td>
            <td><a href="#" class="invite-cancel" data-invite-id="<?= $invite['invite_id'] ?>">Отменить</a></td>
        </tr>
    <?php
    endforeach;
    endif;
    ?>
    </tbody>
</table>
<p><b>Отправить приглашение</b></p>
<form id="create-invite" method="post" action="">
    <input type="text" id="invitee-name" class="form-control" placeholder="имя получателя" required>
    <input type="text" id="invitee-mail" class="form-control" placeholder="почта получателя" required>
    <select id="invitee-position" class="form-control" required>
        <option disabled selected style='display:none;'>Роль*</option>
        <option value="admin">Admin</option>
        <option value="worker">Worker</option>
    </select>
    <input class="btn btn-primary" type="submit">
</form>
<script>
    $(document).ready(function () {
        var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
        $('#invites-table').on('click', '.invite-cancel', function () {
            var el = $(this);
            var inviteId = el.data('invite-id');
            var fd = new FormData();
            fd.append('usp', $usp);
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
                    el.closest('tr').remove();
                    console.log(data);
                }
            });
        });

        $('#create-invite').on('submit', function (e) {
            e.preventDefault();
            var inviteeName = $('#invitee-name').val();
            var inviteeMail = $('#invitee-mail').val();
            var inviteePosition = $('#invitee-position').val();
            if (inviteeName && inviteeMail && inviteePosition) {
                $('#btn-restore').prop('disabled', true);
                $('#spinner-restore').removeClass('d-none');

                var fd = new FormData();
                fd.append('usp', $usp);
                fd.append('ajax', 'invite');
                fd.append('module', 'createInvite');
                fd.append('invitee-name', inviteeName);
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
                            var inviteRow = "<tr><td>";
                            inviteRow += invite['invitee_name'];
                            inviteRow += "</td><td>";
                            inviteRow += window.location.hostname.toString() + '/join/' + invite['code'] + '/';
                            inviteRow += "</td><td>";
                            inviteRow += invite['invitee_position'];
                            inviteRow += "</td><td>";
                            inviteRow += invite['invite_date'];
                            inviteRow += "</td><td>";
                            inviteRow += invite['invitee_name'];
                            inviteRow += "</td><td>";
                            inviteRow += invite['status'];
                            inviteRow += "<td><a href=\"#\" class=\"invite-cancel\" data-invite-id=\"";
                            inviteRow += invite['invitee_id'];
                            inviteRow += "\">Отменить</a></td><tr>";
                            $('#body-invites-table').append(inviteRow);
                            console.log(invite);
                        } else {
                            console.log('Произошла ошибка');
                        }
                    },
                });
            }
        })
    })
</script>
