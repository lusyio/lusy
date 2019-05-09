<?php
$commentClass = [
    'comment' => 'report',
    'system' => 'col-sm-6 system',
    'report' => 'report',
    'returned' => 'report',
    'postpone' => 'report',
    'request' => 'report',
]
?>
<div class="mb-3 comment <?= $commentClass[$commentStatus] ?>" id="<?= $c['id'] ?>">
    <div class="position-relative">
        <span class="date"><?= $dc ?></span>
        <img src="/upload/avatar/<?= $c['iduser'] ?>.jpg" class="avatar mr-3">
        <a href="/profile/<?= $c['iduser'] ?>/" class="font-weight-bold"><?= $nameuser ?> <?= $surnameuser ?></a>
        <?php if ($isDeletable && $id == $c['iduser'] && $c['status'] == 'comment'): ?>
            <button type="button" value="#<?= $c['id'] ?>" class="btn btn-link text-danger delc">
                <i class="fas fa-times"></i>
            </button>
        <?php endif; ?>
    </div>
    <p class="mt-1 mb-2"><?= nl2br($c['comment']) ?></p>
    <?php if (count($files) > 0): ?>
        <p class="">Прикрепленнные файлы:</p>
        <?php foreach ($files as $file): ?>
            <?php if ($file['is_deleted']): ?>
                <p class="mt-1 mb-2"><s><?= $file['file_name'] ?></s> (удален)</p>
            <?php else: ?>
                <p class="mt-1 mb-2"><a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a></p>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>