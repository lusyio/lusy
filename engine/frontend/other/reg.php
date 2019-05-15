<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5 col-xl-4 my-5">
            <!-- Heading -->
            <h1 class="display-4 text-center mb-3">
                Регистрация
            </h1>
            <!-- Subheading -->
            <p class="text-muted text-center mb-5">
                Free access to our dashboard.
            </p>
            <!-- Form -->
            <form action="" method="POST">
                <!-- Email address -->
                <div class="form-group">
                    <label>
                        Название компании
                    </label>
                    <input type="text" name="companyName" class="form-control" placeholder="Company name">
                </div>
                <!-- Login -->
                <div class="form-group">
                    <label>
                        Логин администратора
                    </label>
                    <input type="text" name="login" class="form-control" placeholder="Login">
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label>
                        Password
                    </label>
                    <!-- Input group -->
                    <div class="input-group input-group-merge">
                        <!-- Input -->
                        <input type="password" name="password" class="form-control form-control-appended" placeholder="Enter your password">
                        <!-- Icon -->
                        <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="fe fe-eye"></i>
                  </span>
                        </div>
                    </div>
                </div>
                <!-- E-mail -->
                <div class="form-group">
                    <label>
                        E-mail администратора
                    </label>
                    <input type="text" name="email" class="form-control" placeholder="E-mail">
                </div>
                <hr>
                <!-- Submit -->
                <button class="btn btn-lg btn-block btn-primary mb-3">
                    Зарегистрироваться
                </button>
                <!-- Link -->
                <div class="text-center">
                    <small class="text-muted text-center">
                        Уже зарегистрированы? <a href="/login/">Авторизация</a>.
                    </small>
                </div>
            </form>
        </div>
    </div> <!-- / .row -->
</div> <!-- / .container -->