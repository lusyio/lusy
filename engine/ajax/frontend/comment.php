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
                <?php if (count($files) > 0): ?>
                    <div class="attached-files d-flex flex-wrap">
                        <?php foreach ($files as $file): ?>
                            <?php if ($file['is_deleted']): ?>
                                <p class="mt-2 mb-2 text-secondary file">
                                    <s>
                                        <i class="fas fa-paperclip"></i> <?= $file['file_name'] ?>
                                    </s>
                                    (удален)</p>
                            <?php else: ?>
                                <?php if (in_array($file['extension'],['png', 'jpeg', 'jpg', 'bmp'])): ?>
                                    <div class="photo-preview-container-task-hover mr-2 mb-2">
                                        <div data-target=".bd-example-modal-xl" data-toggle="modal" class="mb-4 text-secondary photo-preview-container text-secondary file photo-preview-container-task clear_fix">
                                            <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview" target="_blank" style="pointer-events: none;background-image: url('/<?= $file['file_path']; ?>')"
                                               href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                                            <p class="small text-muted-new text-center photo-preview-area-message m-0">
                                                <?= $file['file_name'] ?>
                                            </p>
                                        </div>
                                        <div class="photo-preview-background text-center" data-target=".bd-example-modal-xl" data-toggle="modal">
                                            <span class="photo-preview-background-icon"><i class="fas fa-external-link-alt text-white"></i></span>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="photo-preview-container-task-hover mr-2 mb-2">
                                        <div class="text-secondary photo-preview-container mb-4 photo-preview-container-task clear_fix">
                                            <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview" target="_blank" style="background-size: contain;background-image: url('/upload/file.png')"
                                               href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                                        class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                                            <p class="small text-muted-new text-center photo-preview-area-message m-0">
                                                <?= $file['file_name'] ?>
                                            </p>
                                        </div>
                                        <a target="_blank" href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>">
                                            <div class="photo-preview-background text-center">
                                                <span class="photo-preview-background-icon"><i class="fas fa-external-link-alt text-white"></i></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('.photo-preview-background').on('click', function () {
        $this = $(this).siblings('.photo-preview-container');
        var name = $this.find('.photo-preview').text();
        var src = $this.find('.photo-preview').attr('href');
        var size = ($this.find('.photo-preview').attr('sizeFile')/1024/1024).toFixed(2);
        $('.image-modal').attr('src', src);
        $('.image-preview-open').attr('href', src);
        $('.photo-preview-name').text(name);
        $('.image-preview-file-size').text(size + 'мб');
    });
</script>
