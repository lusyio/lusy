<div class="row">
    <div class="col-12">
        <div class="card shadow-none">
            <div class="card-header dialogs p-0">
                <div class="mb-1 p-3">
                    <div class="d-inline-block"></div>
                    <div class="d-inline-block position-absolute" style="right: 16px">
                        <button id="collapseTrigger" data-toggle="tooltip" data-placement="bottom" title="Начать диалог" class="google-icon-btn min ripple" data-target="#collapseUsers"
                                aria-expanded="false" aria-controls="collapseExample">+
                        </button>
                    </div>
                </div>
                <div class="collapse list-group" id="collapseUsers">
                    <a href="#" class="list-group-item list-group-item-action new-dialog-list border-0 open-send-modal" data-send-to="ceo" style="background-color: #fcfcfc">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-bold mt-1">Отправить руководителям</div>
                            </div>
                        </div>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action new-dialog-list border-0 open-send-modal" data-send-to="all" style="background-color: #fcfcfc">
                        <div class="row">
                            <div class="col">
                                <div class="font-weight-bold mt-1">Отправить всем пользователям</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php foreach ($messagesGroupedByCompany as $companyId => $companyMessages): ?>
        <div class="company-support">
            <a class="company-support-link text-decoration-none text-dark" href="#">
                <div class="card mb-3 dialog-mail">
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-area-message">
                                <p class="mb-2 font-weight-bold <?= ($companyMessages['newMessages']) ? 'text-warning' : ''; ?>"><?= $companyMessages['companyName'] ?><?= ($companyMessages['newMessages']) ? ' +' . $companyMessages['newMessages'] : ''; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php foreach ($companyMessages['dialogs'] as $userId => $lastMessage): ?>
            <div class="user-support d-none">
                <a class="text-decoration-none text-dark" href="./<?= $userId ?>/">
                    <div class="card mb-3 dialog-mail">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 pl-2">
                                    <div class="user-pic position-relative" style="width:60px">
                                        <img src="/<?= getAvatarLink($userId) ?>" class="avatar-img rounded-circle w-100 <?= ($lastMessage['role'] == 'ceo') ? 'border-warning' : '' ?>"/>
                                    </div>
                                </div>
                                <div class="col text-area-message" style="max-width: 82%;">
                                    <p class="mb-2 font-weight-bold <?= ($lastMessage['newMessages']) ? 'text-warning' : ''; ?>"><?= (trim($lastMessage['name'] . ' ' . $lastMessage['surname']) == '') ? $lastMessage['email'] : trim($lastMessage['name'] . ' ' . $lastMessage['surname']) ?><?= ($lastMessage['newMessages']) ? ' +' . $lastMessage['newMessages'] : ''; ?>
                                    </p>
                                    <span><?= ($lastMessage['message']['sender'] == 1) ? 'Вы: ' : ''; ?> <?= $lastMessage['message']['mes'] ?></span>
                                    <span class="date mr-2"><?= date('d.m H:i', $lastMessage['message']['datetime']); ?></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="sendMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Отправить сообщение</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3 font-weight-bold">
                Это сообщение будет отправлено <span id="sendToModalText"></span>
                </div>
                <input type="hidden" id="messageTo" value="">
                <textarea id="messageText" class="form-control" placeholder="Текст сообщения" rows="7"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" id="sendMessage" class="btn btn-primary" data-dismiss="modal">Отправить</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script>
    $('#collapseTrigger').on('click', function () {
        $('#collapseUsers').collapse('toggle')
    });

    $('.company-support-link').on('click', function (e) {
        e.preventDefault();
        var userElements = $(this).closest('.company-support').find('.user-support');
        if (userElements.hasClass('d-none')){
            userElements.removeClass('d-none');
        } else {
            userElements.addClass('d-none');
        }
    });

    $('.open-send-modal').on('click', function (e) {
        e.preventDefault();
        var sendTo = $(this).data('send-to');
        console.log(sendTo);
        var modalText = $('#sendToModalText');
        if (sendTo === 'ceo') {
            $('#messageTo').val('ceo');
            modalText.text('всем руководителям');
        } else if (sendTo === 'all') {
            modalText.text('всем пользователям');
            $('#messageTo').val('all');
        }
        $('#sendMessageModal').modal('show');
    });

    $('#sendMessageModal').on('hide.bs.modal', function () {
        $('#messageTo').val('');
    });

    $('#sendMessage').on('click', function () {
        var fd = new FormData;
        fd.append('ajax','messenger');
        fd.append('module', 'sendToAll');
        fd.append('sendTo', $('#messageTo').val());
        fd.append('message', $('#messageText').val());
        if ($('#messageText')) {
            $.ajax({
                url: '/ajax.php',
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: fd,
                success: function (response) {
                    console.log(response);
                    if (response === '') {
                        location.reload();
                    }
                }
            });
        }
    });
</script>
