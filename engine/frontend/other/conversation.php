<div class="card">
    <div class="card-header">
        <h4 class="mb-0"><?= fiomess($recipientId) ?></h4>
    </div>
    <div class="card-body" id="chatBox">
        <?php if ($messages): ?>
            <?php foreach ($messages as $message): ?>
                <p><?= $message['author'] ?> (<?= $message['datetime'] ?>):</p>
                <p><?= $message['mes'] ?></p>
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
        </form>
    </div>
</div>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    var $usp = <?php echo $id + 345;  // айдишник юзера ?>;
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?=$id?>, user_key: '<?=$hash?>', node: "app.comet-server.ru"});
        cometApi.subscription("msg.new", function (e) {
            console.log(e);
            $('#chatBox').append(e.data);
        });
        $('#sendBtn').on('click', function () {
            var mes = $("#mes").val();
            var fd = new FormData();
            fd.append('module', 'sendMessage');
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
                    },
                });
            }
        })
    })
</script>



