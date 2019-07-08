<div class="card">
    <div class="card-header text-center bg-mail">
        <div class="position-absolute">
            <a data-toggle="tooltip" data-placement="bottom" title="Назад к диалогам" class="text-left" href="/mail/"><i
                        class="fas fa-arrow-left icon-invite"></i></a>
        </div>


        <div>
            <a href="/company/" class="mb-0 h5">Чат компании</a>
        </div>
    </div>
    <div class="card-body p-0 border-bottom" id="chatBox">
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <?php include __ROOT__ . '/engine/frontend/other/message.php'; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-messages"><?= $GLOBALS['_emptyconversation'] ?></div>
        <?php endif; ?>
    </div>
</div>
<div class="card border-top bg-mail">
    <div class="card-body pb-0 pl-2 pr-2">
        <form>
            <div class="d-flex send-mes-block">
                <div class="form-group w-100 text-area d-flex">
                    <textarea style="overflow:hidden; padding-right: 45px;" class="form-control mr-2" id="mes" name="mes" rows="1"
                              placeholder="<?= $GLOBALS['_enterconversation'] ?>"></textarea>
                    <?php $uploadModule = 'chat'; ?>
                    <?php include __ROOT__ . '/engine/frontend/other/upload-module.php'; ?>
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
        <div class="file-name container-files" style="display: none"></div>
    </div>
</div>
<?php if ($tariff == 1): ?>
    <script type="text/javascript">
        //=======================Google Drive==========================
        //=Create object of FilePicker Constructor function function & set Properties===
        function SetPicker() {
            var picker = new FilePicker(
                {
                    apiKey: 'AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc',
                    clientId: '34979060720-4dmsjervh14tqqgqs81pd6f14ed04n3d.apps.googleusercontent.com',
                    buttonEl: document.getElementById("openGoogleDrive"),
                    onClick: function (file) {
                    }
                });
        }

        //====================Create POPUP function==============
        function PopupCenter(url, title, w, h) {
            var left = (screen.width / 2) - (w / 2);
            var top = (screen.height / 2) - (h / 2);
            return window.open(url, title, 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        }

        //===============Create Constructor function==============
        function FilePicker(User) {
            //Configuration
            this.apiKey = User.apiKey;
            this.clientId = User.clientId;
            //Button
            this.buttonEl = User.buttonEl;
            //Click Events
            this.onClick = User.onClick;
            this.buttonEl.addEventListener('click', this.open.bind(this));
            //Disable the button until the API loads, as it won't work properly until then.
            this.buttonEl.disabled = true;
            //Load the drive API
            gapi.client.setApiKey(this.apiKey);
            gapi.client.load('drive', 'v2', this.DriveApiLoaded.bind(this));
            gapi.load('picker', '1', {callback: this.PickerApiLoaded.bind(this)});
        }

        FilePicker.prototype = {
            //==========Check Authentication & Call ShowPicker() function=======
            open: function () {
                var token = gapi.auth.getToken();
                if (token) {
                    this.ShowPicker();
                } else {
                    this.DoAuth(false, function () {
                        this.ShowPicker();
                    }.bind(this));
                }
            },
            //========Show the file picker once authentication has been done.=========
            ShowPicker: function () {
                var accessToken = gapi.auth.getToken().access_token;
                var DisplayView = new google.picker.DocsView().setIncludeFolders(true);
                this.picker = new google.picker.PickerBuilder().addView(DisplayView).enableFeature(google.picker.Feature.MULTISELECT_ENABLED).setAppId(this.clientId).setOAuthToken(accessToken).setCallback(this.PickerResponse.bind(this)).setTitle('Google Drive').setLocale('ru').build().setVisible(true);
            },
            //====Called when a file has been selected in the Google Picker Dialog Box======
            PickerResponse: function (data) {
                if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
                    var gFiles = data[google.picker.Response.DOCUMENTS];
                    gFiles.forEach(function (file) {
                        console.log(file);
                        addFileToList(file.name, file.url, file.sizeBytes, 'google-drive', 'fab fa-google-drive');
                    });
                }
            },
            //====Called when file details have been retrieved from Google Drive========
            GetFileDetails: function (file) {
                if (this.onClick) {
                }
            },
            //====Called when the Google Drive file picker API has finished loading.=======
            PickerApiLoaded: function () {
                this.buttonEl.disabled = false;
            },
            //========Called when the Google Drive API has finished loading.==========
            DriveApiLoaded: function () {
                this.DoAuth(true);
            },
            //========Authenticate with Google Drive via the Google Picker API.=====
            DoAuth: function (immediate, callback) {
                gapi.auth.authorize({
                    client_id: this.clientId,
                    scope: 'https://www.googleapis.com/auth/drive',
                    immediate: immediate
                }, callback);
            }
        };

        //=======================Dropbox==========================
        options = {
            success: function (files) {
                files.forEach(function (file) {
                    addFileToList(file.name, file.link, file.bytes, 'dropbox', 'fab fa-dropbox');
                })
            },
            linkType: "direct", // or "preview"
            multiselect: true, // or false
            folderselect: false, // or true
        };
        $('#openDropbox').on('click', function () {
            Dropbox.choose(options);
        });

        //===================End of Dropbox=======================
        function addFileToList(name, link, source, icon) {
            $(".file-name").show().append("<div class='filenames attached-" + source + "-file' data-name='" + name + "' data-link='" + link + "'>" +
                "<i class='fas fa-paperclip mr-1'></i> <i class='" + icon + " mr-1'></i>" + name +
                "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                "</div>");
        }
    </script>
    <script src="https://www.google.com/jsapi?key=AIzaSyCC_SbXTsL3nMUdjotHSpGxyZye4nLYssc"></script>
    <script src="https://apis.google.com/js/client.js?onload=SetPicker"></script>
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
            data-app-key="pjjm32k7twiooo2"></script>
<?php endif; ?>

<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $("#mes").keypress(function (e) {
        var str = $('#mes').val().trim();
        if (str !== '' && typeof str !== undefined) {
            if (e.which == 13 && e.ctrlKey) {
                $('#mes').val($('#mes').val() + "\n");
            } else if (e.which == 13) {
                $("#sendBtn").click();
                $("#mes").val('');
                setTimeout(function () {
                    $("#mes").css('height', '38px');
                }, 300);
            }
        }
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


    var $userId = <?=$id?>;
    var pageName = 'chat';
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id: $userId, user_key: '<?=$cometHash?>', node: "app.comet-server.ru"});
        cometApi.subscription("<?=getCometTrackChannelName()?>", function (e) {
            console.log(e.server_info.event);
            if (e.server_info.event === 'newChat') {
                var fd = new FormData();
                fd.append('messageId', e.data.messageId);
                fd.append('module', 'updateChat');
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
                        $('#chatBox').append(response).scrollTop($("#chatBox")[0].scrollHeight);

                    },
                });
            }

        });

        <?php if ($tariff == 0):?>
        $('#openGoogleDrive, #openDropbox').attr('data-target', '#premModal');
        <?php endif; ?>

        $(".file-name").on('click', '.cancelFile', function () {
            $(this).closest(".filenames").remove();
            var num = parseInt($(this).closest(".filenames").attr('val'));
            fileList.delete(num);
            if (fileList.size === 0) {
                $('.file-name').hide();
            }
        });

        $('.attach-file').on('click', function (e) {
            e.preventDefault();
            $('#sendFiles').trigger('click');
        });

        var fileList = new Map();
        var names;
        var n = 0;
        var size;

        $("#sendFiles").bind('change', function () {
            $(this.files).each(function () {
                names = this.name;
                size = this.size;
                var sizeLimit = $('.dropdown').attr('empty-space');
                if (size <= sizeLimit) {
                    fileList.set(n, $(this)[0]);
                    $(".file-name").show().append("<div val='" + n + "' class='filenames'>" +
                        "<i class='fas fa-paperclip mr-1'></i>" + names +
                        "<i class='fas fa-times cancel-file ml-1 mr-3 d-inline cancelFile'></i>" +
                        "</div>");
                    n++;
                } else {
                    $("#fileSizeLimitModal").modal('show');
                }
            });
        });


        $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);

        $('#chatBox').on('click', '.not-my-message', function () {
            var name = $(this).find('.sender-name').text();
            var text = $('#mes').val();
            if (text.indexOf(name) < 0) {
                $('#mes').val(name + ', ' + text);
            }
        });

        var marker = true;

        function count() {
            marker = false;
        }

        $('#sendBtn').on('click', function () {
            var mes = $("#mes").val();
            var fd = new FormData();
            fd.append('module', 'sendMessageToChat');
            fileList.forEach(function (file, i) {
                fd.append('file' + i, file);
            });
            fd.append('ajax', 'messenger');
            fd.append('mes', mes);
            if (mes.trim() !== '' && typeof mes.trim() !== undefined) {
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
                        fileList = new Map();
                        $('.file-name').hide();
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
                fd.append('module', 'markChatMessageAsRead');
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
        });


        $('#chatBox').on('click', '.delete-message', function () {
            var el = $(this).closest('.message');
            var messageId = $(el).data('message-id');
            var fd = new FormData();
            fd.append('ajax', 'messenger');
            fd.append('module', 'deleteMessage');
            fd.append('messageId', messageId);
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function () {
                    $(el).remove();
                },
            });
        })
    })
</script>