<div class="mb-3 message <?= ($message['owner']) ? 'my-message' : 'not-my-message'; ?> <?= ($message['view_status'] || $message['sender'] == $id) ? '' : 'alert-primary' ?>">
    <div class="row">
        <div class="col-1">
            <img src="/./upload/avatar/<?=$message['sender']?>.jpg" class="avatar">
        </div>
        <div class="col">
            <p class="m-0"><?= $message['author'] ?> <?= $message['datetime'] ?> <?=$message['status']?>:</p>
            <p class="m-0"><?= nl2br($message['mes']) ?></p>
            <?php if (count($message['files']) > 0): ?>
                <?php foreach ($message['files'] as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="m-0"><s><?= $file['file_name'] ?></s> (удален)</p>
                    <?php else: ?>
                        <p class="m-0"><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
