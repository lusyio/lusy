<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/cropper.js"></script>
<link href="/assets/css/cropper.css" rel="stylesheet">

<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-10">
        <div class="card">
            <div class="card-body p-4">
                <div class="row">
                    <a class="float-left" href="/profile/"><i class="fas fa-arrow-left icon-invite"></i></a>
                    <div class="col-6 text-center">
                        <label class="label" data-toggle="tooltip" title=""
                               data-original-title="<?= $GLOBALS['_changeavatarsettings'] ?>">
                            <a class="float-right <?= (strpos(getAvatarLink($id), 'alter')) ? 'd-none' : ''; ?>"
                               href="#" id="deleteAvatar" title="Удалить аватар">
                                <i class="fas fa-times icon-invite"></i>
                            </a>
                            <img class="rounded-circle" id="avatar" src="/<?= getAvatarLink($id) ?>" alt="avatar">
                            <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                        </label>
                        <div id="progress-settings" class="progress" style="display: none;">
                            <div class="progress-bar-settings progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100" style="width: 100%;">0%
                            </div>
                        </div>
                        <div class="alert alert-success" role="alert" style="display: none;"></div>
                        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                             style="display: none;"
                             aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="modalLabel"><?= $GLOBALS['_cutavatarsettings'] ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <div class="col text-center align-center">
                        <h4><?= $userData['name']; ?> <?= $userData['surname']; ?></h4>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-6">
                        <div class="input-group">
                            <input id="settingsName" name="settingsName" type="text" class="form-control name"
                                   value="<?= $userData['name']; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <input id="settingsSurname" name="settingsSurname" type="text"
                                   class="form-control surname" value="<?= $userData['surname']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-7">
                        <div class="input-group">
                            <textarea rows="3" id="settingsDescription" name="settingsDescription" type="text"
                                      class="form-control name"
                                      placeholder="<?= $GLOBALS['_aboutsettings'] ?>"><?= $userData['about']; ?></textarea>
                        </div>
                    </div>
                    <div class="col pl-0">
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-vk"></i></span>
                            </div>
                            <input id="settingsVk" name="settingsVk" type="text"
                                   class="form-control email" placeholder="vk.com"
                                   value="<?= (isset($userData['social']['vk'])) ? $userData['social']['vk'] : ''; ?>">
                        </div>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i
                                            class="fab fa-facebook-square"></i></span>
                            </div>
                            <input id="settingsFacebook" name="settingsFacebook" type="text" placeholder="facebook"
                                   class="form-control email"
                                   value="<?= (isset($userData['social']['facebook'])) ? $userData['social']['facebook'] : ''; ?>">
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i
                                            class="fab fa-instagram"></i></span>
                            </div>
                            <input id="settingsInstagram" name="settingsInstagram" type="text" placeholder="instagram"
                                   class="form-control email"
                                   value="<?= (isset($userData['social']['instagram'])) ? $userData['social']['instagram'] : ''; ?>">
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="input-group">
                            <input id="settingsEmail" name="settingsEmail" type="email"
                                   class="form-control email" value="<?= $userData['email']; ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <!--                                    <button class="rounded-left class=btn border-secondary btn-outline-light dropdown-toggle select-country"-->
                            <!--                                            type="button" data-toggle="dropdown" aria-haspopup="true"-->
                            <!--                                            aria-expanded="false">-->
                            <!--                                        <div class="flag d-inline">🇷🇺</div>-->
                            <!--                                    </button>-->
                            <!--                            <select id="countryNumber" class="custom-select select-country rounded-left">-->
                            <!--                                <option value="7" class="flag">🇷🇺 +7</option>-->
                            <!--                                <option value="1" class="flag">🇺🇸 +1</option>-->
                            <!--                            </select>-->
                            <input id="settingsPhoneNumber" name="settingsPhoneNumber" type="tel"
                                   placeholder="<?= $GLOBALS['_phonesettings'] ?>"
                                   class="form-control phone-number" value="<?= $userData['phone']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <div class="input-group">
                            <input id="settingsNewPassword" name="settingsNewPassword" type="password"
                                   class="form-control new-password"
                                   placeholder="<?= $GLOBALS['_newpasswordsettings'] ?>">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <input id="password" name="password" type="password" class="form-control password"
                                   placeholder="<?= $GLOBALS['_passwordsettings'] ?>"
                                   required>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col text-center">
                        <button class="btn btn-outline-primary" id="sendChanges" type="submit">
                            <?= $GLOBALS['_savesettings'] ?>
                        </button>
                    </div>
                </div>
                <?php if ($roleu == 'ceo'): ?>
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="text-reg text-center mb-3">
                            <?= $GLOBALS['_companysettings'] ?>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="input-group">
                            <input id="companyName" name="companyName" type="text" class="form-control company-name"
                                   value="<?= $companyData['idcompany'] ?>">
                        </div>
                        <div>
                            <small class="text-muted text-muted-reg">
                                <?= $GLOBALS['_namecompanysettings'] ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="input-group">
                            <input id="companyFullName" name="companyFullName" type="text"
                                   class="form-control company-full-name"
                                   value="<?= $companyData['full_company_name'] ?>">
                        </div>
                        <div>
                            <small class="text-muted text-muted-reg">
                                <?= $GLOBALS['_fullnamecompanysettings'] ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="input-group">
                            <textarea id="companyDescription" name="companyDescription" type="text"
                                      class="form-control company-description"><?= $companyData['description'] ?></textarea>
                    </div>
                    <div>
                        <small class="text-muted text-muted-reg">
                            <?= $GLOBALS['_aboutcompanysettings'] ?>
                        </small>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="input-group">
                            <input id="companySite" name="companySite" type="text" class="form-control company-site"
                                   value="<?= $companyData['site'] ?>">
                        </div>
                        <div>
                            <small class="text-muted text-muted-reg">
                                <?= $GLOBALS['_websitecompanysettings'] ?>
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="input-group">
                            <select id="companyTimezone" name="companyTimezone"
                                    class="form-control company-timezone">
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
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="input-group">
                            <input id="companyPassword" name="companyPassword" type="text"
                                   class="form-control company-password"
                                   value="">
                        </div>
                        <div>
                            <small class="text-muted text-muted-reg">
                                <?= $GLOBALS['_enterpasswordcompanysettings'] ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col text-center">
                    <button class="btn btn-outline-primary" id="sendCompanyChanges" type="submit">
                        <?= $GLOBALS['_savecompanysettings'] ?>
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
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
            var newPassword = $("#settingsNewPassword").val();
            var password = $("#password").val();
            // var countryNumber = $("#countryNumber").val();
            // console.log(countryNumber);
            social[socialVk] = vk;
            social[socialFacebook] = facebook;
            var fd = new FormData();

            fd.append('ajax', 'settings');
            fd.append('module', 'changeData');
            fd.append('name', name);
            fd.append('surname', surname);
            fd.append('email', email);
            fd.append('phone', phoneNumber);
            fd.append('newPassword', newPassword);
            fd.append('password', password);
            fd.append('about', description);
            fd.append('vk', vk);
            fd.append('facebook', facebook);
            fd.append('instagram', instagram);
            console.log(password);
            if (password) {
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
            } else {
                $("#password").addClass('border-danger');
            }
        });
        $("#sendCompanyChanges").on('click', function () {
            var companyName = $('#companyName').val();
            var companyFullName = $('#companyFullName').val();
            var companySite = $('#companySite').val();
            var companyDescription = $('#companyDescription').val();
            var companyTimezone = $('#companyTimezone').val();
            var companyPassword = $('#companyPassword').val();

            var fd = new FormData();
            fd.append('ajax', 'settings');
            fd.append('module', 'changeCompanyData');
            fd.append('companyName', companyName);
            fd.append('companyFullName', companyFullName);
            fd.append('companyDescription', companyDescription);
            fd.append('companySite', companySite);
            fd.append('companyTimezone', companyTimezone);
            fd.append('companyPassword', companyPassword);

            if (companyPassword) {
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
            } else {
                $("#companyPassword").addClass('border-danger');
            }
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
                avatar.src = canvas.toDataURL();
                $progress.show();
                $alert.removeClass('alert-success alert-warning');
                $alert.css({'position': 'absolute', 'z-index': '1'})
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
                            var newLink = $('.user-img').attr('src').replace('-alter', '').replace('png', 'png?' + new Date().getTime());
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
                });
            }
        });
    });
</script>