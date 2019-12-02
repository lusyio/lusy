<div class="d-flex flex-wrap">
    <?php foreach ($files as $file): ?>
        <?php if ($file['is_deleted']): ?>
            <div class="deleted-files-container mr-2 mb-2">
                <div class="deleted-file-icon text-center">
                    <i class="far fa-times-circle"></i>
                </div>
                <p class="photo-preview-area-message m-0 text-center text-muted-new small file">
                    <s>
                        <?= $file['file_name'] ?>
                    </s>
                </p>
            </div>
        <?php else: ?>
            <?php if (in_array($file['extension'], ['png', 'jpeg', 'jpg', 'bmp'])): ?>
                <div class="photo-preview-container-task-hover mr-2 mb-2">
                    <div class="text-secondary photo-preview-container photo-preview-container-task clear_fix mb-4">
                        <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview"
                           target="_blank"
                           style="pointer-events: none;background-image: url('/<?= $file['file_path']; ?>')"
                           href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                    class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                        <p class="small text-muted-new text-center photo-preview-area-message m-0">
                            <?= $file['file_name'] ?>
                        </p>
                    </div>
                    <div class="photo-preview-background text-center" data-target=".bd-example-modal-xl"
                         data-toggle="modal">
                                        <span class="photo-preview-background-icon"><i
                                                    class="fas fa-search text-white"></i></span>
                    </div>
                </div>
            <?php else: ?>
                <div class="photo-preview-container-task-hover mr-2 mb-2">
                    <div class="text-secondary photo-preview-container photo-preview-container-task clear_fix mb-4">
                        <a sizeFile="<?= $file['file_size'] ?>" class="text-secondary photo-preview"
                           target="_blank"
                           style="background-size: contain;background-image: url('/upload/file.png')"
                           href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><i
                                    class="fas fa-paperclip"></i> <?= $file['file_name'] ?></a>
                        <p class="small text-muted-new text-center photo-preview-area-message m-0">
                            <?= $file['file_name'] ?>
                        </p>
                    </div>
                    <a target="_blank"
                       href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>">
                        <div class="photo-preview-background text-center">
                                            <span class="photo-preview-background-icon"><i
                                                        class="fas fa-external-link-alt text-white"></i></span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
</div>


