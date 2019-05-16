<div class="container">
    <h1>Приглашение в компанию <?= $companyName; ?></h1>
    <form id="join-form" method="post" action="">
        <input type="text" id="invite-code" hidden value="<?= $code; ?>">
        <input type="text" id="invitee-mail" class="form-control" placeholder="email" value="<?= $email; ?>">
        <input type="text" id="invitee-name" class="form-control" placeholder="name">
        <input type="text" id="invitee-surname" class="form-control" placeholder="surname">
        <input type="text" id="invitee-password" class="form-control" placeholder="password">
        <input type="submit" class="btn btn-primary" value="Зарегистрироваться">
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#join-form').on('submit', function (e) {
            console.log('clicked');
            e.preventDefault();
            var inviteCode = $('#invite-code').val();
            var inviteeMail = $('#invitee-mail').val();
            var inviteeName = $('#invitee-name').val();
            var inviteeSurname = $('#invitee-surname').val();
            var inviteePassword = $('#invitee-password').val();
            if (inviteeMail && inviteeName && inviteeSurname && inviteePassword) {
                var fd = new FormData();
                fd.append('usp', '3456');
                fd.append('ajax', 'reg');
                fd.append('module', 'joinUser');
                fd.append('inviteCode', inviteCode);
                fd.append('inviteeMail', inviteeMail);
                fd.append('inviteeName', inviteeName);
                fd.append('inviteeSurname', inviteeSurname);
                fd.append('inviteePassword', inviteePassword);
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        console.log(data);
                        location.href = '/../login/';
                    }
                })
            }
        })
    })

</script>