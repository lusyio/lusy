<?php if (count($message['files']) > 0): ?>

    <?php foreach ($message['files'] as $file): ?>
        <div class="rounded-0 message <?= ($message['owner']) ? 'my-message' : 'not-my-message'; ?> <?= ($message['view_status'] || $message['sender'] == $id) ? '' : 'new-message' ?>">
            <div class="row">
                <div class="col-2 col-lg-1"></div>
                <div class="col pl-2 message-width">
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
                            <div data-target=".bd-example-modal-xl" data-toggle="modal"
                                 class="m-0 photo-preview-container pb-4 photo-preview-container-messages clear_fix">
                                <a sizeFile="<?= $file['file_size'] ?>" target="_blank" class="photo-preview"
                                   style="pointer-events: none;background-image: url('/<?= $file['file_path']; ?>')"
                                   href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
                                <p class="small text-muted-new text-center photo-preview-area-message m-0">
                                    <?= $file['file_name'] ?>
                                </p>
                                <div class="photo-preview-background photo-preview-background-mes text-center" data-target=".bd-example-modal-xl" data-toggle="modal">
                                    <span class="photo-preview-background-icon"><i class="fas fa-search text-white"></i></span>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="pb-4 photo-preview-container-task-hover disable-transform">
                                <div class="photo-preview-container photo-preview-container-files mb-3 clear_fix"><a
                                        sizeFile="<?= $file['file_size'] ?>" target="_blank"
                                        class="photo-preview" style="background-image: url('/upload/file.png')"
                                        href="<?= ($file['cloud'] == 1) ? $file['file_path'] : '../../' . $file['file_path']; ?>"><?= $file['file_name'] ?></a>
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
                </div>
            </div>
        </div>
    <?php endforeach; ?>

<?php endif; ?>
