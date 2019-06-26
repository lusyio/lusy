<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom dialogs p-0">
                <div class="row mb-1 p-3">
                    <div class="col"><h4 class="mb-0 mt-2"><?= $GLOBALS['_mailconversation'] ?></h4></div>
                    <div class="col-4 text-right">
                        <button class="google-icon-btn min ripple" data-toggle="collapse" data-target="#collapseUsers"
                                aria-expanded="false" aria-controls="collapseExample">+
                        </button>
                    </div>
                </div>
                <div class="collapse list-group" id="collapseUsers">
                    <?php
                    foreach ($userList as $user){
                        $isOnline = in_array($user['id'], $onlineUsersList);
                     ?>
                        <a href="./<?= $user['id'] ?>/"
                           class="list-group-item list-group-item-action new-dialog-list">
                            <div class="row">
                                <div class="col-2 pl-2 col-lg-1">
                                    <div class="avatar-mail-list">
                                        <img src="/<?= getAvatarLink($user['id']) ?>"
                                             class="avatar-img rounded-circle w-100"/>
                                        <span class="online-indicator dialog-list">
                                            <i class="fas fa-circle mr-1 ml-1 onlineIndicator dialog-list-mail <?= ($isOnline) ? 'text-success' : '' ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="font-weight-bold mt-1"><?= $user['name'] . ' ' . $user['surname'] ?></div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="list-group dialog">
                <?php
                foreach ($dialog as $n) {
                    $newMessages = numberOfNewMessages($n);
                    $lastMessage = lastmess($n);
                    $isOnline = in_array($n, $onlineUsersList) ?>
                    <div class="dialog-mail border-secondary p-3">
                        <a class="text-decoration-none text-dark" href="./<?= $n ?>/">
                            <div class="row">
                                <div class="col-2 pl-2">
                                    <div class="user-pic position-relative" style="width:85px">
                                        <img src="/<?= getAvatarLink($n) ?>"
                                             class="avatar-img rounded-circle w-100"/>
                                        <span class="online-indicator mobile-online-indicator">
                                            <i class="fas fa-circle mr-1 ml-1 onlineIndicator mail <?= ($isOnline) ? 'text-success' : '' ?>"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col" style="max-width: 83%;">
                                    <p class="mb-2 font-weight-bold <?= ($newMessages) ? 'text-warning' : ''; ?>"><?= fiomess($n) ?><?= ($newMessages) ? ' +' . $newMessages : ''; ?>
                                        <!--                                        <span id="dialog--->
                                        <? //= $n ?><!--"-->
                                        <!--                                              class="badge badge-success -->
                                        <? //= ($isOnline) ? '' : 'd-none' ?><!--">Online</span>-->
                                    </p>
                                    <span><?= ($lastMessage['sender'] == $id)? 'Вы: ' : '';?> <?= $lastMessage['mes'] ?></span>
                                    <span class="date mr-2"><?= date('d.m H:i', $lastMessage['datetime']); ?></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>





