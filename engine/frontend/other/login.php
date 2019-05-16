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
                Еще не зарегистрированы? <a href="/reg/">Регистрация</a>.
              </small>
            </div>


          </form>
            <div id="btn-show-restore" class="text-center">
                <small class="text-muted text-center">
                    <a href="#" id="btn-show-restore-form">Забыли пароль?</a>
                </small>
            </div>

            <!-- Form -->
            <form id="restore-password" class="d-none">


                <!-- E-mail -->
                <div  class="form-group">

                    <!-- Label -->
                    <label>
                        Введите свой e-mail
                    </label>

                    <!-- Input -->
                    <input id="email-restore" type="text" name="email" class="form-control" placeholder="e-mail" value="">

                </div>

                <!-- Submit -->
                <button id="btn-restore" class="btn btn-lg btn-block btn-primary mb-3">
                    <span id="spinner-restore" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span> Восстановить пароль
                </button>


            </form>
            <p id="restore-result" class="text-center d-none"></p>
        </div>
      </div> <!-- / .row -->
    </div> <!-- / .container -->
  <script>
      $(document).ready(function () {
          $('#btn-show-restore-form').on('click', function () {
              $(this).closest('div').addClass('d-none');
              $('#restore-password').removeClass('d-none');
          });

          $('#restore-password').on('submit', function (e) {
              e.preventDefault();
              var email = $('#email-restore').val();
              if(email) {
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
                      success: function(data){
                          $('#spinner-restore').addClass('d-none');
                          var resultArea = $('#restore-result');
                          if (data) {
                              if (data === 'empty') {
                                  $('#btn-restore').prop('disabled', false);
                                  console.log('отправлена пустая форма')
                              } else {
                                  $('#restore-password').addClass('d-none');
                                  resultArea.removeClass('d-none');
                                  resultArea.text('ссылка для сброса пароля отправлена на почту');
                                  console.log('ссылка для сброса пароля отправлена на почту');
                                  console.log(data);
                              }
                          } else {
                              $('#btn-restore').prop('disabled', false);
                              resultArea.removeClass('d-none');
                              resultArea.text('такого e-mail нет в базе');
                              console.log('такого e-mail нет в базе');
                          }
                      },
                  });
              }
          })
      });
  </script>