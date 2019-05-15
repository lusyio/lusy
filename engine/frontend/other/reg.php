<script src="/assets/js/jquery.steps.js"></script>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5 col-xl-4 my-5">
            <form name="regForm" action="" method="POST">
                <h1 class="display-4 text-center mb-3">
                    Регистрация
                </h1>
                <div id="wizard">
                    <h5></h5>
                    <section>
                        <div class="form-group mt-3 mb-0">
                            <label>
                                Название компании <i class="far fa-question-circle reg-icon"></i>
                            </label>
                            <input type="text" id="companyName" name="companyName" class="form-control"
                                   placeholder="Company name">
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
                                       placeholder="Название компании">
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
                                Логин администратора <i class="far fa-question-circle reg-icon"></i>
                            </label>
                            <input id="loginAdmin" type="text" name="login" class="form-control" placeholder="Login">
                        </div>
                        <div class="form-group">
                            <label>
                                E-mail администратора*
                            </label>
                            <input id="emailAdmin" type="text" name="email" class="form-control" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label>
                                Password*
                            </label>
                            <div class="input-group input-group-merge">
                                <input id="password" type="password" name="password"
                                       class="form-control form-control-appended" placeholder="Enter your password">
                                <div class="input-group-append">
                                    <!--                  <span class="input-group-text">-->
                                    <!--                    <i class="fe fe-eye"></i>-->
                                    <!--                  </span>-->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                Confirm password*
                            </label>
                            <div class="input-group input-group-merge">
                                <input id="confirmPassword" type="password" class="form-control form-control-appended"
                                       placeholder="Enter your password">
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
                        <!--                        <hr>-->
                        <!--                        <button class="btn btn-lg btn-block btn-primary mb-3">-->
                        <!--                            Зарегистрироваться-->
                        <!--                        </button>-->
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

    $(document).ready(function () {
        $(".last").on('click', function () {
            //step1
            var companyName = $("#companyName").val();
            var fullnameCompany = $("#fullnameCompany").val();
            var siteCompany = $("#siteCompany").val();
            var descriptionCompany = $("#descriptionCompany").val();

            //step2
            var loginAdmin = $("#loginAdmin").val();
            var emailAdmin = $("#emailAdmin").val();
            var password = $("#password").val();
            var confirmPassword = $("#confirmPassword").val();

            if (password != confirmPassword) {
                $("#confirmPassword").addClass('border-warning');
                $("#password").addClass('border-warning');
            }

            // if (companyName === ''){
            //     $("#companyName").addClass('border-danger');
            //     console.log('asd');
            // }
            // if (loginAdmin === ''){
            //     $("#loginAdmin").addClass('border-danger');
            //     console.log('asd');
            // }
            // if (emailAdmin === ''){
            //     $("#emailAdmin").addClass('border-danger');
            //     console.log('asd');
            // }


        });
    });


    $("#wizard").steps({
        headerTag: "h5",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,


        onFinished: function (event, currentIndex) {
            //step1
            var companyName = $("#companyName").val();
            var fullnameCompany = $("#fullnameCompany").val();
            var siteCompany = $("#siteCompany").val();
            var descriptionCompany = $("#descriptionCompany").val();

            //step2
            var loginAdmin = $("#loginAdmin").val();
            var emailAdmin = $("#emailAdmin").val();
            var password = $("#password").val();
            var confirmPassword = $("#confirmPassword").val();
            document.regForm.submit();
            alert('reg');
        }


    });


</script>



