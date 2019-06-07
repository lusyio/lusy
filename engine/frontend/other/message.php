<div data-message-id="<?= $message['message_id']?>" class="border-top rounded-0 message <?= ($message['owner']) ? 'my-message' : 'not-my-message'; ?> <?= ($message['view_status'] || $message['sender'] == $id) ? '' : 'alert-primary' ?>">
    <div class="row">
        <div class="col-2 col-lg-1">
            <img src="/<?=getAvatarLink($message['sender'])?>" class="avatar-conversation">
        </div>
        <div class="col pl-2" style="max-width: 91%">
            <p class="m-0"><?= $message['author'] ?> <span class="d-none"><?=$message['status']?></span> <span class="date mr-2"><?= date('d.m H:i', $message['datetime']) ?></span></p>
            <p class="m-0"><?= nl2br($message['mes']) ?></p>
            <?php if (count($message['files']) > 0): ?>
                <?php foreach ($message['files'] as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="m-0"><s><?= $file['file_name'] ?></s> <?= $GLOBALS['_deletedconversation'] ?></p>
                    <?php else: ?>
                        <p class="m-0"><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
