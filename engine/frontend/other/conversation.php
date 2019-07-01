<div class="card">
    <div class="card-header text-center bg-mail">
        <div class="position-absolute">
            <a data-toggle="tooltip" data-placement="bottom" title="Назад к диалогам" class="text-left" href="/mail/"><i
                        class="fas fa-arrow-left icon-invite"></i></a>
        </div>
        <div>
            <a href="/profile/<?= $recipientId ?>/" class="mb-0 h5"><?= fiomess($recipientId) ?>
                <i class="fas fa-circle mr-1 ml-1 onlineIndicator <?= (in_array($recipientId, $onlineUsersList)) ? 'text-success' : '' ?>"></i>
            </a>
        </div>
    </div>
    <div class="card-body p-0 border-bottom" id="chatBox">
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <?php include 'engine/frontend/other/message.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-messages"><?= $GLOBALS['_emptyconversation'] ?></div>
        <?php endif; ?>
    </div>
</div>
<div class="card border-top bg-mail">
    <div class="card-body pb-0">
        <form>
            <div class="d-flex send-mes-block">
                <div class="form-group w-100 mr-2 text-area d-flex">
                    <textarea style="overflow:hidden;" class="form-control" id="mes" name="mes" rows="1"
                              placeholder="<?= $GLOBALS['_enterconversation'] ?>" autofocus></textarea>
                    <span class="btn btn-light btn-file ml-2" data-toggle="tooltip" data-placement="bottom"
                          title="Прикрепить файлы">
                            <i class="fas fa-file-upload custom-date"></i><input id="sendFiles" type="file" multiple>
                        </span>
                </div>
                <div class="position-relative">
                    <input type="button" class="btn btn-primary" id="sendBtn"
                           value="<?= $GLOBALS['_sendconversation'] ?>">
                    <div class="send-mes-tooltip">
                        <div class="card">
                            <div class="send-mes-tooltip-body">
                                <div style="font-size: 13px">
                                    <b>Enter</b>
                                    — Отправить сообщение
                                    <br>
                                    <b>Ctrl+Enter</b>
                                    — Новая строка
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="newmess"></div>
    </div>
</div>
<script>
    $("#mes").keypress(function (e) {
        var str = $('#mes').val().trim();
        if (str !== '' && typeof str !== undefined) {
            if(e.which == 13 && e.ctrlKey) {
                $('#mes').val($('#mes').val() + "\n");
            }
            else if (e.which == 13) {
                $("#sendBtn").click();
                $("#mes").val('');
            }
        }
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    (function (b) {
        b.fn.autoResize = function (f) {
            var a = b.extend({
                onResize: function () {
                }, animate: !0, animateDuration: 150, animateCallback: function () {
                }, extraSpace: 14, limit: 1E3
            }, f);
            this.filter("textarea").each(function () {
                var d = b(this).css({"overflow-y": "hidden", display: "block"}), f = d.height(), g = function () {
                    var c = {};
                    b.each(["height", "width", "lineHeight", "textDecoration", "letterSpacing"], function (b, a) {
                        c[a] = d.css(a)
                    });
                    return d.clone().removeAttr("id").removeAttr("name").css({
                        position: "absolute",
                        top: 0,
                        left: -9999
                    }).css(c).attr("tabIndex", "-1").insertBefore(d)
                }(), h = null, e = function () {
                    g.height(0).val(b(this).val()).scrollTop(1E4);
                    var c = Math.max(g.scrollTop(), f) + a.extraSpace, e = b(this).add(g);
                    h !== c && (h = c, c >= a.limit ? b(this).css("overflow-y", "") : (a.onResize.call(this), a.animate && "block" === d.css("display") ? e.stop().animate({height: c}, a.animateDuration, a.animateCallback) : e.height(c)))
                };
                d.unbind(".dynSiz").bind("keyup.dynSiz", e).bind("keydown.dynSiz", e).bind("change.dynSiz", e)
            });
            return this
        }
    })(jQuery);

    // инициализация
    jQuery(function () {
        jQuery('textarea').autoResize();
    });

    var $recipientId = <?= $recipientId ?>;
    var $userId = <?=$id?>;
    var pageName = 'conversation';
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id: $userId, user_key: '<?=$cometHash?>', node: "app.comet-server.ru"});
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
                        if (e.data.senderId == $userId) {
                            $("#mes").val('');
                        }
                        $('#chatBox').append(response).scrollTop($("#chatBox")[0].scrollHeight);
                        getCounters(function (data) {
                            updateCounters(data);
                        });
                    },
                });
            } else if (e.data.senderId != $userId) {
                updateMessagesCounter();
            }

        });

        var attachedFiles = [];
        var attachedFile = [];

        function sizeFile() {
            $("#sendFiles").bind('change', function () {
                for (var i = 0; i < this.files.length; i++) {
                    var size = this.files[i].size;
                    var names = this.files[i].name;
                    if (size > 20 * 1024 * 1024) {
                        $(".text-area").append("<span id='oversize'>Размер файла превышен</span>");
                        $("#sendBtn").prop('disabled', true);
                    } else {
                        $(".text-area").append("<div class='filenames'>"
                            + names +
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

        // function scrollSmoothToBottom (id) {
        //     var div = document.getElementById(id);
        //     $('#' + id).animate({
        //         scrollTop: div.scrollHeight - div.clientHeight
        //     }, 500);
        // }
        // scrollSmoothToBottom('chatBox');

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
                        if ($('#chatBox').find($('.no-messages')).length) {
                            $('.no-messages').remove();
                        }
                        $("#mes").val('');
                        $(".filenames").html("");
                        attachedFiles = [];

                    },
                });
                $("#mes").removeClass('border-danger');

            } else {
                $("#mes").addClass('border-danger');
            }
        });
        $('#chatBox').on('mouseover', '.message', function () {
            var el = $(this);
            if ($(el).hasClass('new-message')) {
                var messageId = $(el).data('message-id');
                var fd = new FormData();
                fd.append('ajax', 'messenger');
                fd.append('module', 'markMessageAsRead');
                fd.append('messageId', messageId);

                $.ajax({
                    url: '/ajax.php',
                    type: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    data: fd,
                    success: function () {
                        getCounters(function (data) {
                            updateCounters(data);
                        });
                    },
                });
            }
            $(el).removeClass('new-message');
            setTimeout(function () {
                $(el).removeClass('alert-primary');
            }, 500);
        })
    })
</script>




