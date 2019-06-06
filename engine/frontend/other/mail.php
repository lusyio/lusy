<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header dialogs p-0">
                <div class="row mb-1 p-3">
                    <div class="col-10"><h4 class="mb-0"><?= $GLOBALS['_mailconversation'] ?></h4></div>
                    <div class="col-2 text-right">
                        <button class="google-icon-btn min ripple" data-toggle="collapse" data-target="#collapseUsers"
                                aria-expanded="false" aria-controls="collapseExample">+
                        </button>
                    </div>
                </div>
                <div class="collapse list-group" id="collapseUsers">
                    <?php
                    foreach ($userList as $user): ?>
                        <a href="./<?= $user['id'] ?>/" class="list-group-item list-group-item-action rounded-0 border-left-0 border-right-0">
                            <p class="font-weight-bold"><?= $user['name'] . ' ' . $user['surname'] ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="list-group dialog">
                <?php
                foreach ($dialog as $n) {
                    $newMessages = numberOfNewMessages($n);
                    $isOnline = in_array($n, $onlineUsersList)?>
                    <a href="./<?= $n ?>/" class="list-group-item list-group-item-action">
                        <p class="font-weight-bold <?= ($newMessages) ? 'text-warning' : ''; ?>"><?= fiomess($n) ?><?= ($newMessages) ? ' +'.$newMessages : ''; ?> <span id="dialog-<?= $n ?>" class="badge badge-success <?= ($isOnline)? '' : 'd-none' ?>">Online</span></p>
                        <p><?= lastmess($n) ?></p>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>





