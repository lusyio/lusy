<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="/assets/js/jquery.steps.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-7 my-5">
            <h1 class="display-4 text-center mb-3">
                Регистрация
            </h1>
            <form id="regForm" name="regForm" action="" method="POST">
                <h5></h5>
                <section>
                    <div class="form-group mb-0">
                        <div class="text-reg text-center mb-3">
                            Первым делом необходимо выбрать краткий идентификатор для вашей компании
                        </div>
                        <input type="text" id="companyName" name="companyName" class="form-control required"
                               placeholder="Название компании">
                    </div>
                    <div>
                        <small class="text-muted text-muted-reg">
                            Идентификатор компании до 8-ми символов
                        </small>
                    </div>
                </section>

                <h5></h5>
                <section>
                    <div class="text-reg text-center mb-3">
                        Отлично! Осталось создать аккаунт главы компании. Для этого введите email и укажите пароль
                    </div>
                    <div class="form-group">
                        <input id="emailAdmin" type="text" name="email" class="form-control required email"
                               placeholder="E-mail">
                    </div>
                    <div class="form-group mb-0">
                        <div class="input-group">
                            <input id="password" type="password" name="password"
                                   class="form-control required" placeholder="Введите пароль">
                        </div>
                    </div>
                    <div>
                        <small class="text-muted text-muted-reg">
                            Не менее 8 символов
                        </small>
                    </div>
                </section>
            </form>
        </div>
    </div>
    <hr>
    <div class="text-center">
        <small class="text-muted text-center">
            Уже зарегистрированы? <a href="/login/">Авторизация</a>.
        </small>
    </div>
</div>


<script>
    $(document).ready(function () {
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



