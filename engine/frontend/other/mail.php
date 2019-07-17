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
                    <?php
                    foreach ($userList as $user) {
                        $isOnline = in_array($user['id'], $onlineUsersList);
                        ?>
                        <a href="./<?= $user['id'] ?>/"
                           class="list-group-item list-group-item-action new-dialog-list border-0" style="background-color: #fcfcfc">
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

        </div>
        <?php if (empty($supportPage)): ?>
            <?php if ($idc == 1): ?>
                <a class="text-decoration-none text-dark" href="/support/">
                    <div class="card mt-3 mb-3 dialog-mail">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-2 pl-2">
                                <span class="companyAvatar user-pic position-relative"><i
                                            class="fas fa-headset fa-fw"></i></span>
                                </div>
                                <div class="col" style="max-width: 83%;">
                                    <p class="mb-2 font-weight-bold <?= ($newSupportMessages) ? 'text-warning' : ''; ?>">Поддержка пользователей<?= ($newSupportMessages) ? ' +' . $newSupportMessages : ''; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endif; ?>
        <a class="text-decoration-none text-dark" href="/chat/">
            <div class="card mt-3 mb-3 dialog-mail">
                <div class="card-body">
                    <div class="row">
                        <div class="col-2 pl-2">
                                <span class="companyAvatar user-pic position-relative"><i
                                            class="far fa-comments fa-fw"></i></span>
                        </div>
                        <div class="col text-area-message" style="max-width: 82%;">
                            <p class="mb-2 font-weight-bold <?= ($newChatMessages) ? 'text-warning' : ''; ?>">Чат
                                компании<?= ($newChatMessages) ? ' +' . $newChatMessages : ''; ?></p>
                            <?php if ($lastChatMessage): ?>
                                <span><?= ($lastChatMessage['sender'] == $id) ? 'Вы: ' : fiomess($lastChatMessage['sender']) . ': '; ?> <?= $lastChatMessage['mes'] ?></span>
                                <span class="date mr-2"><?= date('d.m H:i', $lastChatMessage['datetime']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">В чате нет сообщений</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        <?php endif; ?>
        <?php
        foreach ($dialog as $n) {
            if (empty($supportPage)) {
                $lastMessage = lastmess($n, $id);
                $newMessages = numberOfNewMessages($n, $id);

            } else {
                $lastMessage = lastmess($n, 1);
                $newMessages = numberOfNewMessages($n, 1);

            }
            $isOnline = in_array($n, $onlineUsersList) ?>
            <a class="text-decoration-none text-dark" href="./<?= $n ?>/">
                <div class="card mb-3 dialog-mail">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2 pl-2">
                                    <?php if($n == 1): ?>
                                    <span class="companyAvatar user-pic position-relative"><i class="fas fa-headset fa-fw"></i></span>
                                    <?php else: ?>
                                <div class="user-pic position-relative" style="width:60px">
                                <img src="/<?= getAvatarLink($n) ?>"
                                         class="avatar-img rounded-circle w-100"/>
                                    <span class="online-indicator mobile-online-indicator">
                                            <i class="fas fa-circle mr-1 ml-1 onlineIndicator mail <?= ($isOnline || $n == 1) ? 'text-success' : '' ?>"></i>
                                        </span>
                                </div>
                                    <?php endif; ?>
                            </div>
                            <div class="col text-area-message" style="max-width: 82%;">
                                <p class="mb-2 font-weight-bold <?= ($newMessages) ? 'text-warning' : ''; ?>"><?= ($n == 1) ? 'Служба поддержки' : fiomess($n) ?><?= ($newMessages) ? ' +' . $newMessages : ''; ?>
                                </p>
                                <span><?= ($lastMessage['sender'] == $id || !empty($supportPage) && $lastMessage['sender'] == 1) ? 'Вы: ' : ''; ?> <?= $lastMessage['mes'] ?></span>
                                <span class="date mr-2"><?= date('d.m H:i', $lastMessage['datetime']); ?></span>
                            </div>
                        </div>

                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>
<script>
    $('#collapseTrigger').on('click', function () {
       $('#collapseUsers').collapse('toggle')
    });
</script>





