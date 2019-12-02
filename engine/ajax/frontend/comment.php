<?php
$commentClass = [
    'comment' => 'comment',
    'system' => 'col-sm-6 system',
    'report' => 'report',
    'returned' => 'report',
    'postpone' => 'report',
    'request' => 'report',
];
if (!is_null($commentViewStatus) && isset($commentViewStatus[$c['manager']])) {
    $commentViewStatusTitleManager = 'Просмотрено ' . $commentViewStatus[$c['manager']]['datetime'];
} else {
    $commentViewStatusTitleManager = 'Не просмотрено';
}
?>
<div class="<?= $commentClass[$commentStatus] ?> <?= ($isNew) ? 'new-event' : '' ?> mb-3" id="<?= $c['id'] ?>">
    <div class="row">
        <div class="col-2 col-lg-1">
            <img src="/<?= getAvatarLink($c['iduser']) ?>" class="avatar mt-1">
        </div>
        <div class="col-10 col-lg-11">
            <div class="position-relative">
				<span class="date text-secondary">
					<?= $dc ?>
                    <?php if ($isDeletable && $id == $c['iduser'] && $c['status'] == 'comment'): ?>
                        <button type="button" value="#<?= $c['id'] ?>"
                                class="btn btn-link text-danger delc position-absolute">
			                <i class="fas fa-times"></i>
			            </button>
                    <?php endif; ?>
				</span>
                <?php
                $fioEmail = DBOnce('email', 'users', 'id=' . $c['iduser']);
                ?>
                <p class="p-0 mb-1 comment-author"><a href="/profile/<?= $c['iduser'] ?>/"
                                                      class="font-weight-bold"><?= (trim($nameuser . $surnameuser) == '') ? $fioEmail : $nameuser . ' ' . $surnameuser ?></a>
                </p>
                <p class="p-0 mb-2 comment-text">
                    <?php
                    if ($commentClass[$commentStatus] == 'report'):
                        ?>
                        <span class="text-muted">
                            Отчет:
                        </span>
                    <?php
                    endif;
                    ?>
                    <?= link_it(nl2br(htmlspecialchars($c['comment']))) ?>
                </p>
                <div class="text-right comment-viewers d-none">
                    <img src="/<?= getAvatarLink($c['manager']) ?>" class="avatar mr-3"
                         title="<?= $commentViewStatusTitleManager ?>">
                    <?php
                    foreach ($coworkers as $coworker):
                        if (!is_null($commentViewStatus) && isset($commentViewStatus[$coworker['worker_id']])) {
                            $commentViewStatusTitle = 'Просмотрено ' . $commentViewStatus[$coworker['worker_id']]['datetime'];
                        } else {
                            $commentViewStatusTitle = 'Не просмотрено';
                        }
                        ?>
                        <img src="/<?= getAvatarLink($coworker['worker_id']) ?>" class="avatar mr-3"
                             title="<?= $commentViewStatusTitle ?>">
                    <?php endforeach; ?>
                </div>
                <?php include __ROOT__ . '/engine/frontend/other/preview-template.php'; ?>
            </div>
        </div>
    </div>
</div>
