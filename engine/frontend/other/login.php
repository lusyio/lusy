<div class="loginPage">
    <div class="container pt-5">
        <div id="login">
            <!-- Heading -->
            <h1 class="lead-text text-white text-center mb-3 mt-5">
                <?= $GLOBALS['_authorization'] ?>
            </h1>

            <!-- Subheading -->
            <p class="lead-text-p text-center">
                <?= $GLOBALS['_entertext'] ?>
            </p>
            <div class="row justify-content-center">
                <div class="col-12 col-md-5 col-xl-4 mt-3">


                    <!-- Form -->
                    <form action="" method="POST">

                        <!-- Email address -->
                        <div class="form-group">

                            <!-- Input -->
                            <input type="text" name="login" class="form-control" placeholder="<?= $GLOBALS['_enteremail'] ?>"
                                   value="">

                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">

                            <!-- Input group -->
                            <div class="input-group input-group-merge">

                                <!-- Input -->
                                <input type="password" name="password" class="form-control form-control-appended"
                                       placeholder="<?= $GLOBALS['_enterpassword'] ?>" value="">


                            </div>
                        </div>


                        <?php
                        if ($loginError == true):
                            ?>
                            <div class="login-error text-center mb-3">
                                <h6>
                                    Вход не удался
                                </h6>
                                <span>
                    Неправильный логин или пароль
                  </span>
                            </div>
                        <?php
                        endif;
                        ?>
                        <!-- Submit -->
                        <button class="btn btn-lg btn-block btn-primary mb-5">
                            Авторизоваться
                        </button>

                        <!-- Link -->
                        <div class="text-center">
                            <p class="text-white text-center mb-0">
                                <?= $GLOBALS['_notregistrated'] ?>? <a href="/reg/" class="text-white"><?= $GLOBALS['_registrationreg'] ?></a>
                            </p>
                        </div>


                    </form>
                    <div id="btn-show-restore" class="text-center">
                        <small class="text-muted text-center">
                            <button class="btn" id="btn-show-restore-form"><?= $GLOBALS['_forgotpassword'] ?>?</button>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div id="restore-password" class="d-none">
            <h1 class="lead-text text-white text-center mb-3 mt-5">
                <?= $GLOBALS['_restorePass'] ?>
            </h1>

            <!-- Subheading -->
            <p class="lead-text-p text-center">
                <?= $GLOBALS['_enteremail'] ?>
            </p>

            <div class="row justify-content-center">
                <div class="col-12 col-md-5 col-xl-4 mt-3">
                    <!-- Form -->
                    <form>


                        <!-- E-mail -->
                        <div class="form-group">


                            <!-- Input -->
                            <input id="email-restore" type="text" name="email" class="form-control"
                                   placeholder="E-mail"
                                   value="">

                        </div>

                        <!-- Submit -->
                        <button id="btn-restore" class="btn btn-lg btn-block btn-primary mb-3">
                        <span id="spinner-restore" class="spinner-border spinner-border-sm d-none" role="status"
                              aria-hidden="true"></span> <?= $GLOBALS['_restorepassword'] ?>
                        </button>


                    </form>
                </div>
            </div>

        </div>
        <p id="restore-result" class="text-center lead-text-p d-none"></p>
    </div>
</div> <!-- / .row -->
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
        $('#btn-show-restore-form').on('click', function () {
            document.title = '<?= $GLOBALS['_restore'] ?>';
            $('#login').empty();
            $(this).closest('div').addClass('d-none');
            $('#restore-password').removeClass('d-none');
        });

        $('#restore-password').on('submit', function (e) {
            e.preventDefault();
            var email = $('#email-restore').val();
            if (email) {
                $('#btn-restore').prop('disabled', true);
                $('#spinner-restore').removeClass('d-none');

                var fd = new FormData();
                fd.append('email', email);
                fd.append('ajax', 'restore-password');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',

                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        $('#spinner-restore').addClass('d-none');
                        var resultArea = $('#restore-result');
                        if (data) {
                            if (data === 'empty') {
                                $('#btn-restore').prop('disabled', false);
                                console.log('отправлена пустая форма')
                            } else {
                                $('#restore-password').addClass('d-none');
                                resultArea.removeClass('d-none');
                                resultArea.text('Ссылка для сброса пароля отправлена на почту!');
                                console.log('ссылка для сброса пароля отправлена на почту');
                                console.log(data);
                            }
                        } else {
                            $('#btn-restore').prop('disabled', false);
                            resultArea.removeClass('d-none');
                            resultArea.text('Данный e-mail отсутствует в базе');
                            console.log('такого e-mail нет в базе');
                            $('#email-restore').val('');
                            setTimeout(function() {
                                $('#restore-result').fadeOut();

                            }, 1500);
                        }
                    },
                });
            }
        })
    });
</script>