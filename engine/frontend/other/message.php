<div data-message-id="<?= $message['message_id'] ?>"
     class="rounded-0 message <?= ($message['owner']) ? 'my-message' : 'not-my-message'; ?> <?= ($message['view_status'] || $message['sender'] == $id) ? '' : 'new-message' ?>">
    <div class="row">
        <div class="col-2 col-lg-1">
            <a class="avatar-chat" href="/profile/<?= $message['sender'] ?>/"><img
                        src="/<?= getAvatarLink($message['sender']) ?>" class="avatar-conversation"></a>
        </div>
        <div class="col pl-2 message-width">
            <?php if (!empty($isCeoAndInChat)): ?>
                <button type="button"
                        class="btn btn-link text-danger delete-message position-absolute" style="right: -15px; top: -15px;">
                    <i class="fas fa-times"></i>
                </button>
            <?php endif; ?>
                <span class="date"><?= date('d.m в H:i', $message['datetime']) ?>
                    </span>
            <p class="m-0" style="color: #000; font-size: 14px; font-weight: 400"><?= link_it(nl2br(htmlspecialchars($message['mes']))) ?></p>
            <?php if (count($message['files']) > 0): ?>
                <?php foreach ($message['files'] as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="m-0" style="color: #000; font-size: 14px; font-weight: 400"><s><?= $file['file_name'] ?></s> <?= $GLOBALS['_deletedconversation'] ?></p>
                    <?php else: ?>
                        <p class="m-0" style="color: #000; font-size: 14px; font-weight: 400"><a class=""
                                          href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>