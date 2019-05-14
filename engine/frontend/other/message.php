<div class="message <?= ($message['owner']) ? 'my-message' : 'not-my-message'; ?> <?= ($message['view_status'] || $message['sender'] == $id) ? '' : 'alert-primary' ?>">
    <p><?= $message['author'] ?> (<?= $message['datetime'] ?>)<?=$message['status']?>:</p>
<p><?= nl2br($message['mes']) ?></p>
<?php if (count($message['files']) > 0): ?>
    <p>Прикрепленные файлы</p>
    <?php foreach ($message['files'] as $file): ?>
        <?php if ($file['is_deleted']): ?>
            <p class=""><s><?= $file['file_name'] ?></s> (удален)</p>
        <?php else: ?>
            <p><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<hr>
