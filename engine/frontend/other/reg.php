<script type="text/javascript" src="/assets/js/popper.min.js"></script>
<div class="loginPage">
    <div class="container pt-5">
        <h1 class="lead-text text-white text-center mb-3 mt-5">
            Регистрация
        </h1>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-7 my-4">
                <form id="regForm" name="regForm" action="" method="POST">
                    <h5></h5>
                    <section>
                        <div class="lead-text-p text-center mb-3">
                            Отлично! Для регистрации введите Ваш email, а пароль мы отправим вам на почту
                        </div>
                        <div class="form-group">
                            <input id="emailAdmin" type="text" value="<?= $login ?>" name="email"
                                   class="form-control required email"
                                   placeholder="E-mail">
                        </div>
                    </section>
                    <div class="row mb-3 repeat-email">
                        <div class="col text-center">
                            <div class="div-repeat-email">
                                <span class="text-white">
                                    <i class="fas fa-exclamation-circle mr-3 icon-repeat-email"></i>
                                    <span>Пользователь с таким email уже существует</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <button id="registrationBtn" class="btn btn-primary" disabled>
                                Зарегистрироваться
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center pb-3">
            <p class="text-white text-center">
                Уже зарегистрированы? <a href="/login/" class="text-white">Авторизация</a>
            </p>
        </div>
        <p class="text-center position-absolute text-footer"><a
                    href="https://lusy.io/">LUSY.IO</a></p>
    </div>

</div>

<div class="modal fade" id="spinnerRegModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0">
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

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
        // получаем из браузера часовой пояс, город пояса и смещение от UTC
        var currentTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        var currentCity = currentTimeZone.split('/')[1];
        //часовой пояс на основе смещения

        // Получаем селект с такой зоной в value и селект с таким городом в тексте
        var timeZoneFromSelect = $('#timezone [value="' + currentTimeZone + '"]');
        var cityFromSelect = $('#timezone option:contains("' + currentCity + '")');
        if (timeZoneFromSelect.length > 0) {
            // если находим зону, то выбираем
            timeZoneFromSelect.attr('selected', true);
        } else if (cityFromSelect.length > 0) {
            // если нет - то выбираем option по совпадению города часовой зоны в тексте
            cityFromSelect.attr('selected', true);
        } else {
            // Последняя попытка определить часовой пояс - на основе смещения времени от UTC  минутах
            var timeOffset = new Date().getTimezoneOffset();

            // функция формирует строку вида 'GMT+HH:MM' или 'GMT-HH:MM'
            function timeZoneByOffset(offset) {
                var hoursOffset = Math.floor(Math.abs(offset / 60));
                var minutesOffset = Math.abs(offset % 60);
                var result = 'GMT';
                (offset <= 0) ? result += '+' : result += '-';
                result += (hoursOffset < 10 ? '0' : '') + hoursOffset;
                result += ':';
                result += (minutesOffset < 10 ? '0' : '') + minutesOffset;
                return result;
            }

            // Ищем в тексте селекта строку GMT....
            $('#timezone option:contains("' + timeZoneByOffset(timeOffset) + '")').attr('selected', true);
        }

        $('[data-toggle="tooltip"]').tooltip();
        $("a[href=\"#previous\"]").addClass('d-none');
        $("a[role=\"menuitem\"]").css({
            'text-align': 'center',
            'margin-bottom': '3%',
            'width': '20rem'
        });

        var securityMail = 0;

        if (securityMail != 1) {
            $("#registrationBtn").prop('disabled', true);
        }

        var email = $('#emailAdmin').val();

        var regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
        var checkMail = regMail.exec(email);

        if (checkMail == null) {
            $('#registrationBtn').prop('disabled', true);
            securityMail = 0;
        } else {
            $('#registrationBtn').prop('disabled', false);
            securityMail = 1;
        }

        $('#emailAdmin').on('keyup', function () {
            var $this = $(this);

            email = $this.val();
            regMail = /^[0-9a-z-\.]+\@[0-9a-z-]{1,}\.[a-z]{2,}$/i;
            checkMail = regMail.exec(email);

            if (checkMail == null) {
                $this.css({
                    'border': '2px solid #fbc2c4',
                    'color': '#8a1f11'
                });
                $('#registrationBtn').prop('disabled', true);
                securityMail = 0;
            } else {
                $this.css({
                    'border': '1px solid #ccc',
                    'color': '#495057'
                });
                $('#registrationBtn').prop('disabled', false);
                securityMail = 1;
            }
            console.log(securityMail)
        });

        $('#registrationBtn').on('click', function () {
            $('#spinnerRegModal').modal('show');
            $('#regForm').submit();
        });
    });
</script>



