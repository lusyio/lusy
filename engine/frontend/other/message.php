<?php include __ROOT__ . '/engine/frontend/other/preview-template-mes.php'; ?>
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
                        class="btn btn-link text-danger delete-message position-absolute">
                    <i class="fas fa-times"></i>
                </button>
            <?php endif; ?>
            <span class="date"><?= date('d.m в H:i', $message['datetime']) ?>
                    </span>
            <p class="m-0"><?= link_it(nl2br(htmlspecialchars($message['mes']))) ?></p>

        </div>
    </div>
</div>

