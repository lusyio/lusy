  <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-5 col-xl-4 my-5">
          
          <!-- Heading -->
          <h1 class="display-4 text-center mb-3">
            Авторизация
          </h1>
          
          <!-- Subheading -->
          <p class="text-muted text-center mb-5">
            Free access to our dashboard.
          </p>

          <!-- Form -->
          <form action="" method="POST">

            <!-- Email address -->
            <div class="form-group">

              <!-- Label -->
              <label>
                Логин
              </label>

              <!-- Input -->
              <input type="text" name="login" class="form-control" placeholder="login" value="richbee">

            </div>

            <!-- Password -->
            <div class="form-group">

              <!-- Label -->
              <label>
                Password
              </label>

              <!-- Input group -->
              <div class="input-group input-group-merge">

                <!-- Input -->
                <input type="password" name="password" class="form-control form-control-appended" placeholder="Enter your password" value="Metro2033228">

                <!-- Icon -->
                <div class="input-group-append">
                  <span class="input-group-text">
                    <i class="fe fe-eye"></i>
                  </span>
                </div>

              </div>
            </div>
            
            <hr>
            
            <!-- Name System -->
            <div class="form-group">

              <!-- Label -->
              <label>
                Идентификатор компании
              </label>

              <!-- Input -->
              <input type="text" name="idcompany" class="form-control" placeholder="login" value="demo">

            </div>

            <!-- Submit -->
            <button class="btn btn-lg btn-block btn-primary mb-3">
              Авторизоваться
            </button>

            <!-- Link -->
            <div class="text-center">
              <small class="text-muted text-center">
                Еще не зарегистрированы? <a href="/reg.php">Регистрация</a>.
              </small>
            </div>

          </form>

        </div>
      </div> <!-- / .row -->
    </div> <!-- / .container -->