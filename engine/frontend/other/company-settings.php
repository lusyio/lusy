<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/cropper.js"></script>
<link href="/assets/css/cropper.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="accordion" id="accordionSettings">
            <?php if ($roleu == 'ceo'): ?>
                <div class="card">
                    <div class="card-header bg-mail">
                        <div class="position-relative">
                            <div class="text-reg" style="font-weight: 300;">
                                <?= $GLOBALS['_companysettings'] ?>
                            </div>
                            <span class="position-absolute edit-settings">
                            <a data-toggle="collapse" href="#collapseCompany" role="button" aria-expanded="false"
                               aria-controls="collapseCompany" class="small text-muted">Изменить</a>
                        </span>
                        </div>
                    </div>
                    <div class="collapse show" id="collapseCompany" aria-labelledby="headingTwo"
                         data-parent="#accordionSettings">
                        <div class="card-body">
                            <div class="input-group">
                                <input id="companyName" name="companyName" type="text"
                                       class="form-control input-settings company-name"
                                       value="<?= $companyData['idcompany'] ?>">
                            </div>
                            <div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_namecompanysettings'] ?>
                                </small>
                            </div>
                            <div class="input-group mt-3">
                                <input id="companyFullName" name="companyFullName" type="text"
                                       class="form-control input-settings company-full-name"
                                       value="<?= $companyData['full_company_name'] ?>">
                            </div>
                            <div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_fullnamecompanysettings'] ?>
                                </small>
                            </div>
                            <div class="input-group mt-3">
                            <textarea id="companyDescription" name="companyDescription" type="text"
                                      class="form-control input-settings  company-description"><?= $companyData['description'] ?></textarea>
                            </div>
                            <div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_aboutcompanysettings'] ?>
                                </small>
                            </div>
                            <div class="input-group mt-3">
                                <input id="companySite" name="companySite" type="text"
                                       class="form-control input-settings company-site"
                                       value="<?= $companyData['site'] ?>">
                            </div>
                            <div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_websitecompanysettings'] ?>
                                </small>
                            </div>
                            <div class="input-group mt-3">
                                <select id="companyTimezone" name="companyTimezone"
                                        class="form-control input-settings company-timezone">
                                    <option></option>
                                    <?php foreach ($timeZones as $timeZone => $text): ?>
                                        <option value="<?= $timeZone ?>" <?= ($companyData['timezone'] == $timeZone) ? 'selected' : '' ?>><?= $text ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_clockcompanysettings'] ?>
                                </small>
                            </div>
                            <div class="text-center mt-5 pb-4">
                                <button class="btn btn-outline-primary" id="sendCompanyChanges" type="submit">
                                    <?= $GLOBALS['_savecompanysettings'] ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header border-top bg-mail">
                    <div class="position-relative">
                        <div class="text-reg" style="font-weight: 300;">
                            Смена пароля
                        </div>
                        <span class="position-absolute edit-settings">
                        <a data-toggle="collapse" href="#collapsePassword" role="button" aria-expanded="false"
                           aria-controls="collapsePassword" class="small text-muted">Изменить</a>
                        </span>
                    </div>
                </div>

                <div class="collapse" id="collapsePassword" aria-labelledby="headingThree"
                     data-parent="#accordionSettings">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <input id="password" name="password" type="password"
                                           class="form-control input-settings password"
                                           required>
                                </div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_passwordsettings'] ?>
                                </small>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <input id="settingsNewPassword" name="settingsNewPassword" type="password"
                                           class="form-control input-settings new-password">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_newpasswordsettings'] ?>
                                </small>
                            </div>
                        </div>
                        <div class="row mt-5 pb-4 text-center">
                            <div class="col-12">
                                <button class="btn btn-outline-primary" id="sendPassword" type="submit">
                                    Сохранить пароль
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-top bg-mail">
                        <div class="position-relative">
                            <div class="text-reg" style="font-weight: 300;">
                                Уведомления
                            </div>
                            <span class="position-absolute edit-settings">
                                <a data-toggle="collapse" href="#collapseNoty" role="button" aria-expanded="false"
                                   aria-controls="collapseNoty" class="small text-muted">Изменить</a>
                            </span>
                            <span class="position-absolute edit-settings edit-settings-saved">
                                <span class="text-muted small">Изменения сохранены</span>
                            </span>
                        </div>
                    </div>

                    <div class="collapse" id="collapseNoty" aria-labelledby="headingFour"
                         data-parent="#accordionSettings">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="fas fa-tasks noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Вам назначена задача</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="taskCreate" <?= ($notifications['task_create']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="fas fa-fire-alt noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Ваша задача просрочена</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="taskOverdue" <?= ($notifications['task_overdue']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="far fa-comment noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Вам оставлен комментарий</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="comment" <?= ($notifications['comment']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="fas fa-file-import noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Вам отправлена задача на рассмотрение</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="taskReview" <?= ($notifications['task_review']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="far fa-calendar-alt noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Запрос на перенос срока</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="taskPostpone" <?= ($notifications['task_postpone']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="far fa-envelope noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Вам оставили сообщение в диалоге</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="message" <?= ($notifications['message']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="fas fa-trophy noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings">
                                        <span>Получено новое достижение</span>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input type="checkbox"
                                               id="achievement" <?= ($notifications['achievement']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $(document).ready(function () {

        function checkInput() {
            var objCheck = {};
            $('input[type=checkbox]').each(function (i, e) {
                objCheck[$(this).attr('id')] = e.checked;
            });
            return objCheck;
        }

        $('input[type=checkbox]').on('change', function () {
            var objCheck = checkInput();
            var fd = new FormData();
            fd.append('ajax', 'settings');
            fd.append('module', 'updateNotifications');
            fd.append('notifications', JSON.stringify(objCheck));
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $('[href="#collapseNoty"]').hide();
                    $('.edit-settings-saved').fadeIn(200);
                    $.when($('.edit-settings-saved').fadeOut(800)).done(function () {
                        $('[href="#collapseNoty"]').fadeIn(200);
                    });
                },
            });
        });

        $("#deleteAvatar").on('click', function (e) {
            e.preventDefault();
            var fd = new FormData();
            fd.append('ajax', 'settings');
            fd.append('module', 'deleteAvatar');
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $("#deleteAvatar").addClass('d-none');
                    var newLink = $('.user-img').attr('src').replace('.png', '-alter.png?' + new Date().getTime());
                    $('#avatar').attr('src', newLink);
                    $('.user-img').attr('src', newLink);
                },
            });
        });

        $("#sendPassword").on("click", function () {
            var newPassword = $("#settingsNewPassword").val();
            var password = $("#password").val();
            var fd = new FormData();

            fd.append('ajax', 'settings');
            fd.append('module', 'changePassword');
            fd.append('newPassword', newPassword);
            fd.append('password', password);
            if (password && newPassword) {
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',

                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        location.reload();
                    },
                });
            } else {
                $("#password").addClass('border-danger');
            }
        });

        $("#sendChanges").on('click', function () {
            var social = {};
            var socialVk = "vk";
            var socialFacebook = "facebook";
            var description = $("#settingsDescription").val();
            var vk = $("#settingsVk").val();
            var facebook = $("#settingsFacebook").val();
            var instagram = $("#settingsInstagram").val();
            var name = $("#settingsName").val();
            var surname = $("#settingsSurname").val();
            var email = $("#settingsEmail").val();
            var phoneNumber = $("#settingsPhoneNumber").val();
            social[socialVk] = vk;
            social[socialFacebook] = facebook;
            var fd = new FormData();

            fd.append('ajax', 'settings');
            fd.append('module', 'changeData');
            fd.append('name', name);
            fd.append('surname', surname);
            fd.append('email', email);
            fd.append('phone', phoneNumber);
            fd.append('about', description);
            fd.append('vk', vk);
            fd.append('facebook', facebook);
            fd.append('instagram', instagram);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (data) {
                    location.reload();
                    console.log('success')
                },
            });
        });
        $("#sendCompanyChanges").on('click', function () {
            var companyName = $('#companyName').val();
            var companyFullName = $('#companyFullName').val();
            var companySite = $('#companySite').val();
            var companyDescription = $('#companyDescription').val();
            var companyTimezone = $('#companyTimezone').val();

            var fd = new FormData();
            fd.append('ajax', 'settings');
            fd.append('module', 'changeCompanyData');
            fd.append('companyName', companyName);
            fd.append('companyFullName', companyFullName);
            fd.append('companyDescription', companyDescription);
            fd.append('companySite', companySite);
            fd.append('companyTimezone', companyTimezone);

            $.ajax({
                url: '/ajax.php',
                type: 'POST',

                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    location.reload();
                },
            });
        })
    });

    window.addEventListener('DOMContentLoaded', function () {
        var avatar = document.getElementById('avatar');
        var image = document.getElementById('image');
        var input = document.getElementById('input');
        var $progress = $('#progress-settings');
        var $progressBar = $('.progress-bar-settings');
        var $alert = $('.alert');
        var $modal = $('#modal');
        var cropper;

        $('[data-toggle="tooltip"]').tooltip();

        input.addEventListener('change', function (e) {
            var files = e.target.files;
            var done = function (url) {
                input.value = '';
                image.src = url;
                $alert.hide();
                $modal.modal('show');
            };
            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
            });
        }).on('hidden.bs.modal', function () {
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('crop').addEventListener('click', function () {
            var initialAvatarURL;
            var canvas;

            $modal.modal('hide');

            if (cropper) {
                canvas = cropper.getCroppedCanvas({
                    width: 190,
                    height: 190,
                    imageSmoothingQuality: 'high',
                });
                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL('image/jpeg', 0.8);
                $progress.show();
                $alert.removeClass('alert-success alert-warning');
                $alert.css({'position': 'absolute', 'z-index': '1'});
                canvas.toBlob(function (blob) {
                    console.log(blob);
                    var fd = new FormData();
                    fd.append('module', 'changeAvatar');
                    fd.append('ajax', 'settings');
                    fd.append('avatar', blob);
                    $.ajax({
                        url: '/ajax.php',
                        type: 'POST',

                        cache: 'false',
                        data: fd,
                        processData: false,
                        contentType: false,

                        xhr: function () {
                            var xhr = new XMLHttpRequest();

                            xhr.upload.onprogress = function (e) {
                                var percent = '0';
                                var percentage = '0%';

                                if (e.lengthComputable) {
                                    percent = Math.round((e.loaded / e.total) * 100);
                                    percentage = percent + '%';
                                    $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                                }
                            };

                            return xhr;
                        },

                        success: function () {
                            $alert.show().fadeIn().addClass('alert-success').text('Загрузка успешна');
                            $("#deleteAvatar").removeClass('d-none');
                            setTimeout(function () {
                                $alert.fadeOut()
                            }, 2000);
                            var newLink = $('.user-img').attr('src').replace('-alter', '').replace('jpg', 'jpg?' + new Date().getTime());
                            $('.user-img').attr('src', newLink);
                        },

                        error: function () {
                            avatar.src = initialAvatarURL;
                            $alert.show().fadeIn().addClass('alert-warning').text('Ошибка при загрузке');
                            setTimeout(function () {
                                $alert.fadeOut()
                            }, 3000)
                        },

                        complete: function () {
                            $progress.hide();
                        },
                    });
                }, "image/jpeg", 0.8);
            }
        });
    });
</script>