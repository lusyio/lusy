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
            <?php if (count($message['files']) > 0): ?>
            <div class="d-flex flex-wrap justify-content-center">
                <?php foreach ($message['files'] as $file): ?>
                    <?php if ($file['is_deleted']): ?>
                        <p class="m-0"><s><?= $file['file_name'] ?></s> <?= $GLOBALS['_deletedconversation'] ?></p>
                    <?php else: ?>
                        <?php if (in_array($file['extension'],['png', 'jpeg', 'jpg', 'bmp'])): ?>
                            <p data-target=".bd-example-modal-xl" data-toggle="modal" class="m-0 mb-2 photo-preview-container photo-preview-container-messages clear_fix"><a sizeFile="<?= $file['file_size'] ?>" target="_blank" class="photo-preview" style="pointer-events: none;background-image: url('/<?= $file['file_path']; ?>')"
                                                                                                                                       href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
                            </p>
                        <?php else: ?>
                        <div class="pb-4 photo-preview-container-task-hover border-0 mr-2 mb-2">
                            <div class="photo-preview-container photo-preview-container-files mb-3 clear_fix"><a sizeFile="<?= $file['file_size'] ?>" target="_blank" class="photo-preview" style="background-image: url('/upload/file.png')"
                                                                                                                                       href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
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

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="photo-preview-name m-0"></h5>
            </div>
            <img class="image-modal" src="">
            <div class="modal-footer" style="justify-content: flex-end">
                <span class="text-muted-new small d-none">
                    Дата загрузки : 15-09-2019
                </span>
                <span class="text-muted-new small">
                    Размер файла : <span class="image-preview-file-size">15 мб</span>
                    |
                    <a class="image-preview-open text-muted-new " href="">Открыть оригинал</a>
                </span>
            </div>
            <span class="icon-close-modal">
            <button type="button" class="btn btn-light rounded-circle" data-dismiss="modal"><i
                        class="fas fa-times text-muted"></i></button>
            </span>
        </div>
    </div>
</div>

<script>

    $('.photo-preview-container').on('click', function () {
        $this = $(this);
        var name = $this.find('.photo-preview').text();
        var src = $this.find('.photo-preview').attr('href');
        var size = ($this.find('.photo-preview').attr('sizeFile')/1024/1024).toFixed(2);
        $('.image-modal').attr('src', src);
        $('.image-preview-open').attr('href', src);
        $('.photo-preview-name').text(name);
        $('.image-preview-file-size').text(size + 'мб');
    });
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


