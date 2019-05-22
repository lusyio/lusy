<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/cropper.js"></script>
<link href="/assets/css/cropper.css" rel="stylesheet">

<!--<img src="/upload/avatar-1.jpg" class="rounded-circle image-profile"><i id="editProfileImage"-->
<!--                                                                        class="fas fa-pencil-alt edit-profileimage"></i>-->


<div class="row justify-content-center">
    <div class="col-10">
        <form id="save-profile">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-5">
                            <label class="label" data-toggle="tooltip" title=""
                                   data-original-title="Смена изображения">
                                <img class="rounded-circle" id="avatar" src="/upload/avatar-1.jpg" alt="avatar">
                                <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                            </label>
                            <div class="progress" style="display: none;">
                                <div class="progress-bar-settings progress-bar-striped progress-bar-animated"
                                     role="progressbar"
                                     aria-valuenow="100"
                                     aria-valuemin="0" aria-valuemax="100" style="width: 100%;">100%
                                </div>
                            </div>
                            <div class="alert alert-success" role="alert" style="display: none;"></div>
                            <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                                 style="display: none;"
                                 aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel">Обрезать изображение</h5>
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
                                                Назад
                                            </button>
                                            <button type="button" class="btn btn-primary" id="crop">Обрезать</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col text-center align-center">
                            <h4>Иван Петрович</h4>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Иван">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Петрович">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="email">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="phonenumber">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="новый пароль">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="старый пароль" required>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col text-center">
                            <button class="btn btn-outline-primary" type="submit">Сохранить изменения</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', function () {
        var avatar = document.getElementById('avatar');
        var image = document.getElementById('image');
        var input = document.getElementById('input');
        var $progress = $('.progress');
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
                    width: 160,
                    height: 160,
                });
                initialAvatarURL = avatar.src;
                avatar.src = canvas.toDataURL();
                $progress.show();
                $alert.removeClass('alert-success alert-warning');
                canvas.toBlob(function (blob) {
                    var formData = new FormData();

                    formData.append('avatar', blob, 'avatar.jpg');
                    $.ajax('https://jsonplaceholder.typicode.com/posts', {
                        method: 'POST',
                        data: formData,
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
                        },

                        error: function () {
                            avatar.src = initialAvatarURL;
                            $alert.show().fadeIn().addClass('alert-warning').text('Ошибка при загрузке');
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