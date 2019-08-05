<div class="login-block container pt-3 pb-5" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-4">

            <a class="navbar-brand-log text-dark text-uppercase font-weight-bold visible-lg mt-1"
               href="https://lusy.io/"><span
                        class="logo mr-3">L</span>LUSY</a>
        </div>
        <div class="col-8">
            <a href="https://lusy.io/ru/register/" class="btn btn-outline-violet float-right">Регистрация</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-5 mb-5">

            <div id="login">
                <!-- Heading -->
                <h2 class="lead-text text-dark mb-5 mt-5">
                    <?= $GLOBALS['_authorization'] ?>
                </h2>

                <!-- Form -->
                <form action="" method="POST">

                    <!-- Email address -->
                    <div class="input-group mb-3">

                        <!-- Input -->
                        <input type="text" name="login" class="form-control"
                               placeholder="<?= $GLOBALS['_enteremail'] ?>"
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
                    <button class="btn btn-lg btn-block text-white btn-violet mb-5">
                        Войти
                    </button>
                    <div id="btn-show-restore">
                        <small class="text-muted">
                            <button class="btn pl-0 text-secondary"
                                    id="btn-show-restore-form"><?= $GLOBALS['_forgotpassword'] ?>?
                            </button>
                        </small>
                    </div>

                </form>

            </div>
            <div id="restore-password" class="d-none">
                <h2 class="lead-text text-dark mb-5 mt-5">
                    <?= $GLOBALS['_restorePass'] ?>
                </h2>

                        <!-- Form -->
                        <form>


                            <!-- E-mail -->
                            <div class="input-group mb-3">


                                <!-- Input -->
                                <input id="email-restore" type="text" name="email" class="form-control"
                                       placeholder="E-mail"
                                       value="">

                            </div>

                            <!-- Submit -->
                            <button id="btn-restore" class="btn btn-lg btn-block btn-violet text-white mb-3">
                        <span id="spinner-restore" class="spinner-border spinner-border-sm d-none" role="status"
                              aria-hidden="true"></span> <?= $GLOBALS['_restorepassword'] ?>
                            </button>


                        </form>

            </div>
            <p id="restore-result" class="text-center text-dark lead-text-p d-none"></p>
        </div>
        <div class="col-md-5 offset-md-1 text-center d-none d-md-block">
            <img src="/upload/mount.jpg" class="mt-5 mount">
        </div>
    </div>
</div>
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
                            setTimeout(function () {
                                $('#restore-result').fadeOut();

                            }, 1500);
                        }
                    },
                });
            }
        })
    });
</script>