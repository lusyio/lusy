<div class="container pt-3 pb-5" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-4">
            <a class="navbar-brand-log text-dark text-uppercase font-weight-bold visible-lg mt-1"
               href="https://lusy.io/"><span
                        class="logo mr-3">L</span>LUSY</a>
        </div>
        <div class="col-8">
            <a href="/login/" class="btn btn-outline-violet float-right">Авторизация</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mt-5 mb-5">
            <h2 class="lead-text text-dark mb-5 mt-5">
                Смена пароля
            </h2>
            <form action="" method="POST">
                <!-- Password -->
                <div class="form-group">
                    <!-- Label -->
                    <!-- Input group -->
                    <div class="input-group input-group-merge">
                        <!-- Input -->
                        <input type="password" name="password" class="form-control form-control-appended"
                               placeholder="Введите новый пароль" required>
                    </div>
                </div>
                <!-- Submit -->
                <button id="restoreNewPassword" class="btn btn-lg btn-block btn-violet text-white mb-3">
                    Сменить пароль
                </button>
            </form>

        </div>
        <div class="col-md-5 offset-md-1 text-center d-none d-md-block">
            <img src="/upload/mount.jpg" class="mt-5 mount">
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $(".btn-violet").addClass('disabled');
            $('[name=password]').on('keyup', function () {
                var $this = $(this);
                var password = $this.val();
                var reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
                var checkPass = reg.exec(password);

                if (checkPass == null) {
                    $this.css({
                        'border': '1px solid #fbc2c4',
                        'color': '#8a1f11'
                    });
                    $(".btn-violet").addClass('disabled');
                } else {
                    $this.css({
                        'border': '1px solid #ccc',
                        'color': '#495057'
                    });
                    $(".btn-violet").removeClass('disabled');
                }
            });
        });
    </script>

