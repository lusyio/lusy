<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="/assets/js/jquery.steps.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5 col-xl-4 my-5">
                <h1 class="display-4 text-center mb-3">
                    Регистрация
                </h1>
                <form id="regForm" name="regForm" action="" method="POST">
                    <h5></h5>
                    <section>
                        <div class="form-group mt-3 mb-0">
                            <label>
                                 Название компании <i data-toggle="tooltip" title="Сокращенное до 5 символов" class="far fa-question-circle reg-icon"></i>
                            </label>
                            <input type="text" id="companyName" name="companyName" class="form-control required"
                                   placeholder="Название компании">
                        </div>
                        <div class="text-center">
                            <small class="text-muted text-center">
                                Уже зарегистрированы? <a href="/login/">Авторизация</a>.
                            </small>
                        </div>
                        <a class="btn btn-outline-secondary btn-sm mt-3" data-toggle="collapse" href="#collapseInfo"
                           role="button" aria-expanded="false" aria-controls="collapseInfo">
                            Дополнительная информация
                        </a>
                        <div class="collapse mt-2" id="collapseInfo">
                            <div class="form-group">
                                <label>
                                    Полное название компании
                                </label>
                                <input id="fullnameCompany" type="text" name="fullnameCompany" class="form-control"
                                       placeholder="Полное название компании">
                            </div>
                            <div class="form-group">
                                <label>
                                    Сайт компании
                                </label>
                                <input id="siteCompany" type="text" name="siteCompany" class="form-control"
                                       placeholder="Company name">
                            </div>
                            <div class="form-group">
                                <label>
                                    Описание компании
                                </label>
                                <textarea id="descriptionCompany" name="descriptionCompany" class="form-control"
                                          aria-label="With textarea"></textarea>
                            </div>
                        </div>
                    </section>

                    <h5></h5>
                    <section>
                        <div class="form-group mt-3">
                            <label>
                                Логин администратора <i data-toggle="tooltip" title="Yahoo!" class="far fa-question-circle reg-icon "></i>
                            </label>
                            <input id="loginAdmin" type="text" name="login" class="form-control required" placeholder="Login">
                        </div>
                        <div class="form-group">
                            <label>
                                E-mail администратора*
                            </label>
                            <input id="emailAdmin" type="text" name="email" class="form-control required email" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label>
                                Пароль*
                            </label>
                            <div class="input-group input-group-merge">
                                <input id="password" type="password" name="password"
                                       class="form-control form-control-appended required" placeholder="Введите пароль">
                                <div class="input-group-append">
                                    <!--                  <span class="input-group-text">-->
                                    <!--                    <i class="fe fe-eye"></i>-->
                                    <!--                  </span>-->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                Подтвердите пароль*
                            </label>
                            <div class="input-group input-group-merge">
                                <input id="confirmPassword" name="confirm" type="password" class="form-control form-control-appended required"
                                       placeholder="Подтвердите пароль">
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
        </div>
    </div>
</div>
</div>

<!--<div class="container">-->
<!--    <div class="row justify-content-center">-->
<!--        <div class="col-12 col-md-5 col-xl-4 my-5">-->
<!---->
<!--            <h1 class="display-4 text-center mb-3">-->
<!--                Регистрация-->
<!--            </h1>-->
<!---->
<!--            <p class="text-muted text-center mb-5">-->
<!--                Free access to our dashboard.-->
<!--            </p>-->
<!---->
<!--            <form action="" method="POST">-->
<!--                Email address -->
<!--                <div class="form-group">-->
<!--                    <label>-->
<!--                        Название компании-->
<!--                    </label>-->
<!--                    <input type="text" name="companyName" class="form-control" placeholder="Company name">-->
<!--                </div>-->
<!--                Login -->
<!--                <div class="form-group">-->
<!--                    <label>-->
<!--                        Логин администратора-->
<!--                    </label>-->
<!--                    <input type="text" name="login" class="form-control" placeholder="Login">-->
<!--                </div>-->
<!--                Password -->
<!--                <div class="form-group">-->
<!--                    <label>-->
<!--                        Password-->
<!--                    </label>-->
<!--                    Input group -->
<!--                    <div class="input-group input-group-merge">-->
<!--                        Input -->
<!--                        <input type="password" name="password" class="form-control form-control-appended" placeholder="Enter your password">-->
<!--                        Icon -->
<!--                        <div class="input-group-append">-->
<!--                  <span class="input-group-text">-->
<!--                    <i class="fe fe-eye"></i>-->
<!--                  </span>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                E-mail -->
<!--                <div class="form-group">-->
<!--                    <label>-->
<!--                        E-mail администратора-->
<!--                    </label>-->
<!--                    <input type="text" name="email" class="form-control" placeholder="E-mail">-->
<!--                </div>-->
<!--                <hr>-->
<!--                Submit -->
<!--                <button class="btn btn-lg btn-block btn-primary mb-3">-->
<!--                    Зарегистрироваться-->
<!--                </button>-->
<!--                Link -->
<!--                <div class="text-center">-->
<!--                    <small class="text-muted text-center">-->
<!--                        Уже зарегистрированы? <a href="/login/">Авторизация</a>.-->
<!--                    </small>-->
<!--                </div>-->
<!--            </form>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->


<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    var form = $("#regForm");


    form.steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanging: function (event, currentIndex, newIndex)
        {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex)
            {
                return true;
            }
            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex)
            {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },

        onFinishing: function (event, currentIndex)
        {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex)
        {
            document.regForm.submit();
        }
    }).validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }

    });


</script>



