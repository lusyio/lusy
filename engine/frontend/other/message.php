<p><?= $message['author'] ?> (<?= $message['datetime'] ?>):</p>
<p><?= $message['mes'] ?></p>
<?php if (count($message['files']) > 0): ?>
    <?php foreach ($message['files'] as $file): ?>
        <p>Прикрепленные файлы</p>
        <p><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
    <?php endforeach; ?>
<?php endif; ?>