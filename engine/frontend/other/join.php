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
                                       placeholder="Пароль не менее 6 символов"
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
<p class="text-center position-absolute text-footer"><a href="https://lusy.io/">LUSY.IO</a></p>
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

        var security = 0;
        var securityMail = 0;
        var securityPass = 0;

        var password = $('#invitee-password').val();
        var reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
        var checkPass = reg.exec(password);

        var email = $('#invitee-mail').val();
        var regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
        var checkMail = regMail.exec(email);

        if (checkMail == null) {
            securityMail = 0;
        } else {
            securityMail = 1;
        }

        if (checkPass == null) {
            securityPass = 0;
        } else {
            securityPass = 1;
        }

        security = securityMail + securityPass;

        if (security != 2) {
            $('[type=submit]').prop('disabled', true);
        } else {
            $('[type=submit]').prop('disabled', false);
        }

        $('#invitee-name').on('change', function () {
            var $this = $(this);

            if ($this.val() != ''){
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
            } else {
                $this.css({
                    'border': '2px solid #fbc2c4',
                    'color': '#8a1f11'
                });
            }
        });

        $('#invitee-surname').on('change', function () {
            var $this = $(this);

            if ($this.val() != ''){
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
            } else {
                $this.css({
                    'border': '2px solid #fbc2c4',
                    'color': '#8a1f11'
                });
            }
        });

        $('#invitee-mail').on('keyup', function () {
            var $this = $(this);

            email = $this.val();
            regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
            checkMail = regMail.exec(email);

            if (checkMail == null) {
                $this.css({
                    'border': '2px solid #fbc2c4',
                    'color': '#8a1f11'
                });
                securityMail = 0;
            } else {
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
                securityMail = 1;
            }
            security = securityMail + securityPass;
            if (security == 2) {
                $("[type=submit]").prop('disabled', false);
            } else {
                $("[type=submit]").prop('disabled', true);
            }
        });

        $('#invitee-password').on('keyup', function () {
            var $this = $(this);
            password = $this.val();
            reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
            checkPass = reg.exec(password);

            if (checkPass == null) {
                $this.css({
                    'border': '2px solid #fbc2c4',
                    'color': '#8a1f11'
                });
                securityPass = 0;
            } else {
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
                securityPass = 1;
                $('[type=submit]').prop('disabled', false);
            }
            security = securityMail + securityPass;
            if (security == 2) {
                $("[type=submit]").prop('disabled', false);
            } else {
                $("[type=submit]").prop('disabled', true);
            }
        });

        $('#join-form').on('submit', function (e) {
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
                        if (!data) {
                            location.href = '/../login/';
                        }
                    }
                })
            }
        })
    })

</script>
