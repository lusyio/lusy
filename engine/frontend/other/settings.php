<script src="/assets/js/cropper.js"></script>
<script src="/assets/js/jquery.mask.min.js"></script>
<link href="/assets/css/cropper.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="accordion" id="accordionSettings">
            <div class="card">
                <a class="text-decoration-none accordion-link" href="#collapseProfile" data-toggle="collapse"
                   role="button" aria-expanded="false"
                   aria-controls="collapseProfile">
                    <div class="card-header bg-mail">
                        <div class="position-relative">
                            <div class="text-reg">
                                Настройки профиля
                            </div>
                            <span class="position-absolute edit-settings">
                                <span id="editSpan0" class="small text-muted">Изменить</span>
                                </span>
                            <span class="position-absolute edit-settings edit-settings-saved" settings-id="1">
                                <span class="text-muted small">Изменения сохранены</span>
                            </span>
                        </div>
                    </div>
                </a>
                <div class="collapse show" id="collapseProfile" aria-labelledby="headingOne"
                     data-parent="#accordionSettings">
                    <div class="text-center position-absolute spinner-settings">
                        <div class="spinner-border"
                             role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-5 col-lg-5 text-center">
                                <a class="float-right <?= (strpos(getAvatarLink($id), 'alter')) ? 'd-none' : ''; ?>"
                                   href="#" id="deleteAvatar" data-toggle="tooltip" data-placement="bottom"
                                   title="Удалить аватар">
                                    <i class="fas fa-times icon-invite"></i>
                                </a>
                                <label class="label" data-toggle="tooltip" title=""
                                       data-original-title="<?= $GLOBALS['_changeavatarsettings'] ?>">
                                    <img class="rounded-circle avatar-settings" id="avatar"
                                         src="/<?= getAvatarLink($id) ?>"
                                         alt="avatar">
                                    <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                                </label>
                                <div class="modal fade display-none" id="modal" data-backdrop="static" tabindex="-1" role="dialog"
                                     aria-labelledby="modalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="modalLabel"><?= $GLOBALS['_cutavatarsettings'] ?></h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="img-container">
                                                    <img id="image"
                                                         src=""
                                                         class="">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <?= $GLOBALS['_backsettings'] ?>
                                                </button>
                                                <button type="button" class="btn btn-primary"
                                                        id="crop"><?= $GLOBALS['_uploadsettings'] ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col text-center align-center fio-profile">
                                <?php if ($userData['name'] != null && $userData['surname'] != null): ?>
                                    <div class="fio-profile"><?= $userData['name'] ?> <?= $userData['surname'] ?></div>
                                <?php else: ?>
                                    <div class="fio-profile"><?= $userData['email'] ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row pt-4">
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <input id="settingsName" name="settingsName" type="text"
                                           class="form-control input-settings name text-center"
                                           value="<?= $userData['name']; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Имя
                                </small>
                            </div>
                            <div class="col-6">
                                <div class="input-group mt-3">
                                    <input id="settingsSurname" name="settingsSurname" type="text"
                                           class="form-control input-settings surname text-center"
                                           value="<?= $userData['surname']; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Фамилия
                                </small>
                            </div>
                        </div>
                        <div class="input-group mt-3">
                    <textarea rows="3" id="settingsDescription" name="settingsDescription" type="text"
                              class="form-control input-settings  name"><?= $userData['about']; ?></textarea>
                        </div>
                        <small class="text-muted text-muted-reg">
                            <?= $GLOBALS['_aboutsettings'] ?>
                        </small>
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <div class="input-group mt-3">
                                    <input type="date" id="bDayDate" class="form-control input-settings"
                                           value="<?= $userData['birthdate']; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Дата рождения
                                </small>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fab fa-vk"></i>
                            </span>
                                    </div>
                                    <input id="settingsVk" name="settingsVk" type="text"
                                           class="form-control input-settings email"
                                           value="<?= (isset($userData['social']['vk'])) ? $userData['social']['vk'] : ''; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Вконтакте
                                </small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fab fa-facebook-square"></i>
                                </span>
                                    </div>
                                    <input id="settingsFacebook" name="settingsFacebook" type="text"
                                           class="form-control input-settings email"
                                           value="<?= (isset($userData['social']['facebook'])) ? $userData['social']['facebook'] : ''; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Facebook
                                </small>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="fab fa-instagram"></i>
                                        </span>
                                    </div>
                                    <input id="settingsInstagram" name="settingsInstagram" type="text"
                                           class="form-control input-settings email"
                                           value="<?= (isset($userData['social']['instagram'])) ? $userData['social']['instagram'] : ''; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Instagram
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <input id="settingsEmail" name="settingsEmail" type="email"
                                           class="form-control input-settings email" value="<?= $userData['email']; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    Электронная почта
                                </small>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="input-group mt-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+</span>
                                    </div>
                                    <input id="settingsPhoneNumber" name="settingsPhoneNumber" type="text"
                                           class="form-control input-settings phone-number"
                                           value="<?= $userData['phone']; ?>">
                                </div>
                                <small class="text-muted text-muted-reg">
                                    <?= $GLOBALS['_phonesettings'] ?>
                                </small>
                            </div>
                        </div>
                        <div class="row mt-5 pb-4">
                            <div class="col-12 text-center">
                                <button class="btn btn-outline-primary" id="sendChanges" type="submit">
                                    <?= $GLOBALS['_savesettings'] ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <a class="text-decoration-none accordion-link" data-toggle="collapse" href="#collapsePassword"
                   role="button" aria-expanded="false"
                   aria-controls="collapsePassword">
                    <div class="card-header border-top bg-mail">
                        <div class="position-relative">
                            <div class="text-reg">
                                Смена пароля
                            </div>
                            <span class="position-absolute edit-settings">
                                <span id="editSpan1" class="small text-muted">Изменить</span>
                                </span>
                            <span class="position-absolute edit-settings edit-settings-saved" settings-id="2">
                                <span class="text-muted small">Изменения сохранены</span>
                            </span>
                        </div>
                    </div>
                </a>

                <div class="collapse" id="collapsePassword" aria-labelledby="headingThree"
                     data-parent="#accordionSettings">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6 offset-lg-3">
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
                    <a class="text-decoration-none accordion-link" data-toggle="collapse" href="#collapseNoty"
                       role="button" aria-expanded="false"
                       aria-controls="collapseNoty">
                        <div class="card-header border-top bg-mail">
                            <div class="position-relative">
                                <div class="text-reg">
                                    Уведомления
                                </div>
                                <span class="position-absolute edit-settings">
                                <span id="editSpan2" class="small text-muted">Изменить</span>
                                </span>
                                <span class="position-absolute edit-settings edit-settings-saved" settings-id="3">
                                <span class="text-muted small">Изменения сохранены</span>
                                </span>
                            </div>
                        </div>
                    </a>


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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
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
                                        <input class="new-checkbox" type="checkbox"
                                               id="achievement" <?= ($notifications['achievement']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($isCeo):
                                ?>
                                <div class="row mt-3">
                                    <div class="col-2">
                                        <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="fas fa-coins fa-fw noty-icon-size-settings"></i>
                            </span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="noty-padding-content-settings">
                                            <span>Уведомления об оплате</span>
                                        </div>
                                    </div>
                                    <div class="col-3 col-lg-2">
                                        <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                            <input class="new-checkbox" type="checkbox"
                                                   id="payment" <?= ($notifications['payment']) ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row mt-3">
                                <div class="col-2">
                                    <div class="rounded-circle text-center noty-icon-settings">
                            <span class="text-white">
                                <i class="far fa-bell-slash noty-icon-size-settings"></i>
                            </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="noty-padding-content-settings d-flex noty-padding-content-settings-sleep">
                                        <span>Не беспокоить</span>
                                        <div class="d-flex sleep-container">
                                            <select class="form-control form-control-sm" id="startSleep">
                                                <option hidden></option>
                                                <?php for ($i = 0; $i < 24; $i++): ?>
                                                    <option value="<?= $i ?>" <?= ($notifications['silence_start'] == $i || ($notifications['silence_start'] == -1 && $i == 21)) ? 'selected' : '' ?>><?= $i ?>
                                                        :00
                                                    </option>
                                                <?php endfor; ?>
                                            </select>–
                                            <select class="form-control form-control-sm" id="endSleep">
                                                <option hidden></option>
                                                <?php for ($i = 0; $i < 24; $i++): ?>
                                                    <option value="<?= $i ?>" <?= ($notifications['silence_end'] == $i || ($notifications['silence_start'] == -1 && $i == 9)) ? 'selected' : '' ?>><?= $i ?>
                                                        :00
                                                    </option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 col-lg-2">
                                    <div class="noty-padding-content-settings noty-padding-checkbox-settings">
                                        <input class="new-checkbox" type="checkbox"
                                               id="sleepTime" <?= ($notifications['silence_start'] == '-1') ? '' : 'checked' ?>>
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

        $('#bDayDate').mask('0000-00-00');
        $('#settingsPhoneNumber').mask('0 (000) 000-00-00');

        $('#sleepTime').on('change', function () {
            if ($(this).is(':checked')) {
                $('.sleep-container').css('opacity', '1');
                $('#endSleep').attr('disabled', false);
                $('#startSleep').attr('disabled', false);
            } else {
                $('.sleep-container').css('opacity', '0.5');
                $('#endSleep').attr('disabled', true);
                $('#startSleep').attr('disabled', true);
            }
        });

        if ($('#sleepTime').is(':checked')) {
            $('.sleep-container').css('opacity', '1');
            $('#endSleep').attr('disabled', false);
            $('#startSleep').attr('disabled', false);
        } else {
            $('.sleep-container').css('opacity', '0.5');
            $('#endSleep').attr('disabled', true);
            $('#startSleep').attr('disabled', true);
        }


        function checkInput() {
            var objCheck = {};
            $('input[type=checkbox]').each(function (i, e) {
                objCheck[$(this).attr('id')] = e.checked;
            });
            $('select option:selected').each(function (i, e) {
                objCheck[$(this).closest('select').attr('id')] = $(this).val();
            });
            return objCheck;
        }

        $('input[type=checkbox], select').on('change', function () {

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
                    $('#editSpan2').hide();
                    $('.edit-settings-saved[settings-id=3]').fadeIn(200);
                    $.when($('.edit-settings-saved[settings-id=3]').fadeOut(800)).done(function () {
                        $('#editSpan2').fadeIn(200);
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

        $('#settingsNewPassword').on('keyup', function () {
            var $this = $(this);
            var password = $this.val();
            var reg = /^[\w~!@#$%^&*()_+`\-={}|\[\]\\\\;\':",.\/?]{6,64}$/;
            var checkPass = reg.exec(password);

            if (checkPass == null){
                $this.css({
                    'border': '1px solid #dc3545',
                    'color': '#dc3545'
                });
                $('#sendPassword').prop('disabled', true)
            } else {
                $this.css({
                    'border': '1px solid #ced4da',
                    'color': '#495057'
                });
                $('#sendPassword').prop('disabled', false)
            }
        });

        $("#sendPassword").on("click", function () {
            var newPassword = $("#settingsNewPassword").val();
            var password = $("#password").val();
            var fd = new FormData();

            fd.append('ajax', 'settings');
            fd.append('module', 'changePassword');
            fd.append('newPassword', newPassword);
            if (newPassword) {
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',

                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        if (data == ''){
                            $("#settingsNewPassword").val('');
                            $('#editSpan1').hide();
                            $('.edit-settings-saved[settings-id=2]').fadeIn(800);
                            $('.edit-settings-saved[settings-id=2] .small').text('Изменения сохранены');
                            setTimeout(function () {
                                $.when($('.edit-settings-saved[settings-id=2]').fadeOut(800)).done(function () {
                                    $('#editSpan1').fadeIn(200);
                                });
                            }, 1500)
                        }
                    },
                });
            } else {
                if (newPassword == '') {
                    $('#settingsNewPassword').css({
                        'border-color': '#dc3545',
                        'transition': '1000ms'
                    });
                    setTimeout(function () {
                        $('#settingsNewPassword').css('border-color', "#ced4da");
                    }, 1000);
                }
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
            var bDayDate = $("#bDayDate").val();

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
            fd.append('birthdate', bDayDate);
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
        });
    });

    window.addEventListener('DOMContentLoaded', function () {
        var avatar = document.getElementById('avatar');
        var image = document.getElementById('image');
        var input = document.getElementById('input');
        var $spinner = $('.spinner-settings');
        var $modal = $('#modal');
        var cropper;

        $('[data-toggle="tooltip"]').tooltip();

        input.addEventListener('change', function (e) {
            var files = e.target.files;
            var done = function (url) {
                input.value = '';
                image.src = url;
                $.when($('.edit-settings-saved[settings-id=1]').fadeOut(800)).done(function () {
                    $('#editSpan0').fadeIn(200);
                });
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
                viewMode: 2,
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
                $spinner.show();
                canvas.toBlob(function (blob) {
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

                        success: function () {
                            $('#editSpan0').hide();
                            $('.edit-settings-saved[settings-id=1]').fadeIn(200);
                            $('.edit-settings-saved[settings-id=1] .small').text('Аватар загружен');
                            $.when($('.edit-settings-saved[settings-id=1]').fadeOut(800)).done(function () {
                                $('#editSpan0').fadeIn(200);
                            });
                            var newLink = $('.user-img').attr('src').replace('-alter', '').replace('jpg', 'jpg?' + new Date().getTime());
                            $('.user-img').attr('src', newLink);
                        },

                        error: function () {
                            avatar.src = initialAvatarURL;
                            $('#editSpan0').hide();
                            $('.edit-settings-saved[settings-id=1]').fadeIn(200);
                            $('.edit-settings-saved[settings-id=1] .small').text('Ошибка при загрузке');
                            $.when($('.edit-settings-saved[settings-id=1]').fadeOut(800)).done(function () {
                                $('#editSpan0').fadeIn(200);
                            });
                        },

                        complete: function () {
                            $spinner.hide();
                            $("#deleteAvatar").removeClass('d-none');
                        },
                    });
                }, "image/jpeg", 0.8);
            }
        });
    });
</script>
