<div class="card">
    <div class="card-header dialogs">
        <div class="row mb-3">
            <div class="col-10"><h4 class="mb-0">Диалоги</h4></div>
            <div class="col-2 text-right">
                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseUsers"
                        aria-expanded="false" aria-controls="collapseExample">+
                </button>
            </div>
        </div>
        <div class="collapse list-group" id="collapseUsers">
            <?php
            foreach ($userList as $user): ?>
                <a href="./<?= $user['id'] ?>/" class="list-group-item list-group-item-action border-0">
                    <p class="font-weight-bold"><?= $user['name'] . ' ' . $user['surname'] ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="list-group">
        <?php foreach ($dialog as $n) {
            $newMessages = numberOfNewMessages($n);?>
            <a href="./<?= $n ?>/" class="list-group-item list-group-item-action border-0">
                <p class="font-weight-bold <?= ($newMessages) ? 'text-warning' : ''; ?>"><?= fiomess($n) ?><?= ($newMessages) ? ' +'.$newMessages : ''; ?></p>
                <p><?= lastmess($n) ?></p>
            </a>
        <?php } ?>
    </div>
</div>
<script src="/assets/js/CometServerApi.js"></script>
<script>
    $(document).ready(function () {
        cometApi.start({dev_id: 2553, user_id:<?= $id ?>, user_key: '<?= $cometHash ?>', node: "app.comet-server.ru"});
        subscribeToMessagesNotification();
    });
</script>



