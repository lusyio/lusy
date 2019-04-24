<?php
$commentClass = [
    'comment' => 'report',
    'system' => 'col-sm-6 system',
    'report' => 'report',
    'returned' => 'report',
    'postpone' => 'report',
]
?>
<div class="mb-3 comment <?= $commentClass[$c['status']] ?>" id="<?= $c['id'] ?>">
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
            <a class="" href="../../<?= $file['file_path'] ?>"><?= $file['file_name'] ?></a>
        <?php endforeach; ?>
    <?php endif; ?>
</div>