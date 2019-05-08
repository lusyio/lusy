

<div class="card">
    <div class="card-header">
        <h4 class="mb-0"><?= fiomess($recipientId) ?></h4>
    </div>
    <div class="card-body" id="chatBox">
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <?php include 'engine/frontend/other/message.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-messages">Нет сообщений</p>
        <?php endif; ?>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">Новое сообщение</h4>
    </div>
    <div class="card-body">
        <form>
            <div class="form-group">
                <label for="mes">Сообщение</label>
                <textarea class="form-control" id="mes" name="mes" rows="3" placeholder="Текст сообщения"
                          required></textarea>
            </div>
            <input type="button" class="btn btn-primary" id="sendBtn" value="Отправить">
            <span class="btn btn-light btn-file">
                <i class="fas fa-file-upload custom-date"></i><input id="sendFiles" type="file" multiple>
            </span>
        </form>
        <div class="newmess"></div>
    </div>
</div>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?=$id?>, user_key: '<?=$hash?>', node: "app.comet-server.ru"});
        cometApi.subscription("msg.new", function (e) {
            console.log(e);
            var fd = new FormData();
            fd.append('messageId', e.data);
            fd.append('module', 'updateMessages');
            fd.append('ajax', 'messenger');
            fd.append('mes', mes);
            fd.append('usp', $usp);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    console.log(response);
                    if( $('#chatBox').find($('.no-messages')).length) {
                        $('.no-messages').remove();
                    }
                    $("#mes").val('');
                    $('#chatBox').append(response);
                },
            });
        });

        var attachedFiles = [];
        var attachedFile = [];

        function sizeFile(){
            $("#sendFiles").bind('change', function () {
                for (var i = 0; i<this.files.length; i++) {
                    var size = this.files[i].size;
                    var names = this.files[i].name;
                    if (size > 2 * 1024 * 1024) {
                        $(".btn-file").append("<span id='oversize'>Размер файла превышен</span>");
                        $("#sendBtn").prop('disabled', true);
                    } else {
                        $(".newmess").append("<div class='filenames'>"
                            +names+
                            "<i class='fas fa-times custom-date cancel cancel-file ml-2 mr-3 cancelFile'></i>" +
                            "</div>");
                        $("#oversize").remove();
                        $("#sendBtn").prop('disabled', false);

                    }
                    $(".cancelFile").on('click', function () {
                        $(this).closest(".filenames").remove();
                        removeFile();
                    });
                }
            });
        }



        $("#sendFiles").on('click', function () {
            sizeFile();
            $("#sendFiles").off('click');

        });

        var marker = true;

        function count() {
            marker = false;
        }

        function attachFile() {
            // attachedFile = $('input[type=file]')[0].files;
            attachedFile = $('input[type=file]').prop('files')[0];
            attachedFiles.push(attachedFile);
            console.log(attachedFiles);

        }


        function removeFile(e) {
            var file = $(this).data("file");
            for (var i = 0; i < attachedFile.length; i++) {
                if (attachedFile[i].name === file) {
                    attachedFile.splice(i, 1);
                    break;
                }
            }
            $(this).parent().remove();
            $("#sendFiles").val("");
            console.log(attachedFile);
        }


        $('#sendBtn').on('click', function () {
            var mes = $("#mes").val();
            attachFile();
            var fd = new FormData();
            fd.append('module', 'sendMessage');
            fd.append('file', attachedFiles[0]);
            fd.append('file1', attachedFiles[1]);
            fd.append('file2', attachedFiles[2]);
            fd.append('ajax', 'messenger');
            fd.append('recipientId', '<?=$recipientId;?>');
            fd.append('mes', mes);
            fd.append('usp', $usp);
            if (mes) {
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (data) {
                        console.log(data);
                        if( $('#chatBox').find($('.no-messages')).length) {
                            $('.no-messages').remove();
                        }
                        $("#mes").val('');
                        $(".filenames").html("");

                    },
                });
                $("#mes").removeClass('border-danger');
            }else {
                $("#mes").addClass('border-danger');
            }

        })
    })
</script>




