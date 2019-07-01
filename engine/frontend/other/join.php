<div class="join-page">
    <div class="container pt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-7">
                <h1 class="text-white lead-text display-4 text-center mt-5 mb-3">
                    <?= $GLOBALS['_registrationjoin'] ?>
                </h1>
                <p class="lead-text-p text-center"><?= $GLOBALS['_invitedtojoin'] ?> <?= $companyName; ?></p>
                <form  id="join-form" method="post" action="">
                    <div class="join">
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-4">
                                <input type="text" id="invitee-name" class="form-control"
                                       placeholder="<?= $GLOBALS['_placeholdernamejoin'] ?>"
                                       required>
                            </div>
                            <div class="col-12 col-lg-6 mb-4">
                                <input type="text" id="invitee-surname" class="form-control"
                                       placeholder="<?= $GLOBALS['_placeholdersurnamejoin'] ?>"
                                       required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-4">
                                <input type="email" id="invitee-mail" class="form-control" placeholder="E-mail"
                                       value="<?= $email; ?>"
                                       required>
                            </div>
                            <div class="col-12 col-lg-6">
                                <input type="password" id="invitee-password" class="form-control"
                                       placeholder="Пароль не менее 8 символов"
                                       required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <input type="text" id="invite-code" hidden value="<?= $code; ?>">
                            <input type="submit" class="btn btn-primary btn-join"
                                   value="<?= $GLOBALS['_registerjoin'] ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<p class="text-center position-absolute" style="left: 0; right: 0; bottom: 0"><a href="https://lusy.io/">LUSY.IO</a></p>
<style>
    html, body {
        height: 100%;
    }

    .form-control {
        height: calc(2.5em + .75rem + 2px) !important;
        border-radius: 15px !important;
        padding-left: 20px;
    }

    .btn-primary {
        background: #284c8e;
        border: none !important;
        border-radius: 15px;
        padding-left: 25px !important;
        padding-right: 25px !important;
    }

    .btn-primary:hover {
        background: #13387c;
    }

    #btn-show-restore-form {
        color: #e1e1e1;
    }
</style>

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