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
                <p class="p-0 mb-1 comment-author"><a href="/profile/<?= $c['iduser'] ?>/"
                                                      class="font-weight-bold"><?= $nameuser ?> <?= $surnameuser ?></a>
                </p>
                <p class="p-0 mb-2 comment-text"><?= link_it(nl2br(htmlspecialchars($c['comment']))) ?></p>
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
                <?php if (count($files) > 0): ?>
                    <div class="attached-files">
                        <?php foreach ($files as $file): ?>
                            <?php if ($file['is_deleted']): ?>
                                <p class="mt-2 mb-2 text-secondary file">
                                    <s>
                                        <i class="fas fa-paperclip"></i> <?= $file['file_name'] ?>
                                    </s>
                                    (удален)</p>
                            <?php else: ?>
                                <p class="mt-2 mb-2 text-secondary file">
                                    <a class="text-secondary" href="../../<?= $file['file_path'] ?>">
                                        <i class="fas fa-paperclip"></i> <?= $file['file_name'] ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
