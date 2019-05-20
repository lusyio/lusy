

<div class="card">
    <div class="card-header pt-0">
        <h5 class="mb-0"><?= fiomess($recipientId) ?> <i class="fas fa-circle mr-1 ml-1 onlineIndicator"></i></h5>
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
    <div class="card-body pb-0">
        <form>
            <div class="d-flex">
                <div class="form-group w-100 mr-2">
                    <textarea class="form-control" id="mes" name="mes" rows="1" placeholder="Введите сообщение . . ."
                              required></textarea>
                </div>
                <div class="mr-2">
                    <input type="button" class="btn btn-primary" id="sendBtn" value="Отправить">
                </div>
                <div>
                    <span class="btn btn-light btn-file">
                        <i class="fas fa-file-upload custom-date"></i><input id="sendFiles" type="file" multiple>
                    </span>
                </div>
            </div>
        </form>
        <div class="newmess"></div>
    </div>
</div>
<script>
    var $recipientId = <?= $recipientId ?>;
    var $userId = <?=$id?>;
    $(document).ready(function () {
        var numberToSubtract = $('#chatBox .alert-primary').length;
        console.log(numberToSubtract);
        var messagesCount = $('#messagesCount').text();
        messagesCount = messagesCount - numberToSubtract;
        if (messagesCount) {
            $('#messagesCount').text(messagesCount);
        } else {
            $('#messagesIcon').removeClass('text-warning');
            $('#messagesCount').text('');
        }
        cometApi.start({dev_id: 2553, user_id: $userId, user_key: '<?=$GLOBALS['cometHash']?>', node: "app.comet-server.ru"});
        cometApi.subscription("msg.new", function (e) {
            console.log(e);
            if (e.data.senderId == $recipientId && e.data.recipientId == $userId || e.data.senderId == $userId && e.data.recipientId == $recipientId) {
                var fd = new FormData();
                fd.append('messageId', e.data.messageId);
                fd.append('module', 'updateMessages');
                fd.append('ajax', 'messenger');
                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function (response) {
                        console.log(response);
                        if ($('#chatBox').find($('.no-messages')).length) {
                            $('.no-messages').remove();
                        }
                        $("#mes").val('');
                        $('#chatBox').append(response);
                    },
                });
            } else if (e.data.senderId != $userId) {
                updateMesagesCounter();
            }

        });

        var attachedFiles = [];
        var attachedFile = [];

        function sizeFile(){
            $("#sendFiles").bind('change', function () {
                for (var i = 0; i<this.files.length; i++) {
                    var size = this.files[i].size;
                    var names = this.files[i].name;
                    if (size > 20 * 1024 * 1024) {
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

        $(window).resize(function(){
            var small_height = 250;
            var medium_height = 350;
            var big_height = 550;

            if($(window).height() < 600 )
            {
                $('#chatBox').height(small_height);
            }
            else
            {
                $('#chatBox').height(medium_height);
            }
        });

        $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);

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
                        attachedFiles = [];

                    },
                });
                $("#mes").removeClass('border-danger');
            }else {
                $("#mes").addClass('border-danger');
            }

        });
        $('#chatBox').on('mouseover', '.message', function () {
            var el = $(this);
            setTimeout(function () {
                $(el).removeClass('alert-primary');
            }, 500);
        })
    })
</script>




