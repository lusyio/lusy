<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="/assets/js/jquery.steps.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
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
                        <div class="form-group mb-0">
                            <div class="lead-text-p text-center mb-3">
                                Первым делом необходимо выбрать краткий идентификатор для вашей компании
                            </div>
                            <input type="text" id="companyName" name="companyName" class="form-control required"
                                   placeholder="Название компании" autocomplete="off">
                        </div>

                    </section>

                    <h5></h5>
                    <section>

                        <div class="form-group mb-0">
                            <div class="lead-text-p text-center mb-3">
                                Теперь выберите часовой пояс, в котором работает ваша компания, и предпочитаемый язык
                            </div>
                            <select id="timezone" name="timezone" class="form-control mb-3 required">
                                <option disabled>Выберите часовой пояс</option>
                                <option value="Etc/GMT+12">(GMT-12:00) International Date Line West</option>
                                <option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
                                <option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
                                <option value="US/Alaska">(GMT-09:00) Alaska</option>
                                <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
                                <option value="America/Tijuana">(GMT-08:00) Tijuana, Baja California</option>
                                <option value="US/Arizona">(GMT-07:00) Arizona</option>
                                <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                <option value="US/Mountain">(GMT-07:00) Mountain Time (US & Canada)</option>
                                <option value="America/Managua">(GMT-06:00) Central America</option>
                                <option value="US/Central">(GMT-06:00) Central Time (US & Canada)</option>
                                <option value="America/Mexico_City">(GMT-06:00) Guadalajara, Mexico City, Monterrey
                                </option>
                                <option value="Canada/Saskatchewan">(GMT-06:00) Saskatchewan</option>
                                <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                <option value="US/Eastern">(GMT-05:00) Eastern Time (US & Canada)</option>
                                <option value="US/East-Indiana">(GMT-05:00) Indiana (East)</option>
                                <option value="Canada/Atlantic">(GMT-04:00) Atlantic Time (Canada)</option>
                                <option value="America/Caracas">(GMT-04:00) Caracas, La Paz</option>
                                <option value="America/Manaus">(GMT-04:00) Manaus</option>
                                <option value="America/Santiago">(GMT-04:00) Santiago</option>
                                <option value="Canada/Newfoundland">(GMT-03:30) Newfoundland</option>
                                <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires, Georgetown
                                </option>
                                <option value="America/Godthab">(GMT-03:00) Greenland</option>
                                <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                                <option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
                                <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                                <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                <option value="Africa/Casablanca">(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                <option value="Etc/Greenwich">(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh,
                                    Lisbon, London
                                </option>
                                <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm,
                                    Vienna
                                </option>
                                <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana,
                                    Prague
                                </option>
                                <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                <option value="Europe/Sarajevo">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                <option value="Africa/Lagos">(GMT+01:00) West Central Africa</option>
                                <option value="Asia/Amman">(GMT+02:00) Amman</option>
                                <option value="Europe/Athens">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                <option value="Africa/Harare">(GMT+02:00) Harare, Pretoria</option>
                                <option value="Europe/Helsinki">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn,
                                    Vilnius
                                </option>
                                <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                <option value="Africa/Windhoek">(GMT+02:00) Windhoek</option>
                                <option value="Asia/Kuwait">(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                <option value="Africa/Nairobi">(GMT+03:00) Nairobi</option>
                                <option value="Asia/Tbilisi">(GMT+03:00) Tbilisi</option>
                                <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                <option value="Asia/Muscat">(GMT+04:00) Abu Dhabi, Muscat</option>
                                <option value="Asia/Baku">(GMT+04:00) Baku</option>
                                <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                <option value="Asia/Kabul">(GMT+04:30) Kabul</option>
                                <option value="Asia/Yekaterinburg">(GMT+05:00) Yekaterinburg</option>
                                <option value="Asia/Karachi">(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                <option value="Asia/Calcutta">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                <option value="Asia/Calcutta">(GMT+05:30) Sri Jayawardenapura</option>
                                <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                <option value="Asia/Almaty">(GMT+06:00) Almaty, Novosibirsk</option>
                                <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                                <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                                <option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi
                                </option>
                                <option value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                <option value="Australia/Perth">(GMT+08:00) Perth</option>
                                <option value="Asia/Taipei">(GMT+08:00) Taipei</option>
                                <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                                <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                                <option value="Australia/Canberra">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                <option value="Pacific/Guam">(GMT+10:00) Guam, Port Moresby</option>
                                <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                                <option value="Asia/Magadan">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                                <option value="Pacific/Fiji">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                <option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
                            </select>
                        </div>
                        <div>
                            <div class="form-group mb-0">
                                <select id="language" name="language" class="form-control required">
                                    <option disabled>Выберите язык</option>
                                    <option value="ru" <?= ($userLanguage == 'ru') ? 'selected' : '' ?>>Русский</option>
                                    <option value="en" <?= ($userLanguage == 'en') ? 'selected' : '' ?> disabled>English</option>
                                </select>
                            </div>
                    </section>

                    <h5></h5>
                    <section>
                        <div class="lead-text-p text-center mb-3">
                            Отлично! Осталось создать аккаунт главы компании. Для этого введите Ваш email и укажите пароль
                        </div>
                        <div class="form-group">
                            <input id="emailAdmin" type="text" value="<?=$email?>" name="email" class="form-control required email"
                                   placeholder="E-mail">
                        </div>
                        <div class="form-group mb-0">
                            <div class="input-group">
                                <input id="password" type="password" name="password"
                                       class="form-control required" placeholder="Пароль не менее 8 символов" autocomplete="off">
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        </div>
        <div class="text-center pb-3">
            <p class="text-white text-center">
                Уже зарегистрированы? <a href="/login/" class="text-white">Авторизация</a>
            </p>
        </div>
        <p class="text-center position-absolute" style="left: 0; right: 0; bottom: 0"><a
                    href="https://lusy.io/">LUSY.IO</a></p>
    </div>

</div>

<div class="modal fade" id="spinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0" style="margin-top: 60%;background-color: transparent;">
            <div class="modal-body text-center">
                <div class="spinner-border" style="width: 3rem; height: 3rem;color: #f2f2f2;" role="status">
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
        })
    });

    var form = $("#regForm");
    form.steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }
            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            $('#spinnerModal').modal('show');
            document.regForm.submit();
        }
    }).validate({
        errorPlacement: function errorPlacement(error, element) {
            element.before(error);
        },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });
</script>



