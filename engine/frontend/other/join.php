<div class="container">
    <div class="row justify-content-center">
        <div class="col-7 my-5">
            <h1 class="display-4 text-center mb-3">
                <?= $_registrationjoin ?>
            </h1>
            <form id="join-form" method="post" action="">
                <div class="join mb-3">
                    <div class="text-reg text-center mb-3"><?= $_invitedtojoin ?> <?= $companyName; ?></div>
                    <div class="row">
                        <div class="col-6 mb-4">
                            <input type="text" id="invitee-name" class="form-control" placeholder="<?= $_placeholdernamejoin ?>"
                                   required>
                        </div>
                        <div class="col-6 mb-4">
                            <input type="text" id="invitee-surname" class="form-control" placeholder="<?= $_placeholdersurnamejoin ?>"
                                   required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <input type="email" id="invitee-mail" class="form-control" placeholder="E-mail"
                                   value="<?= $email; ?>"
                                   required>
                        </div>
                        <div class="col-6">
                            <input type="password" id="invitee-password" class="form-control"
                                   placeholder="<?= $_placeholderpasswordjoin ?>"
                                   required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">

                        </div>
                        <div class="col-6">
                            <small class="text-muted text-muted-reg">
                                <?= $_passwordnotyjoin ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <input type="text" id="invite-code" hidden value="<?= $code; ?>">
                        <input type="submit" class="btn btn-primary btn-join" value="<?= $_registerjoin ?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
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
                        if (!data) {
                            location.href = '/../login/';
                        }
                    }
                })
            }
        })
    })

</script>