<?php foreach ($invites as $invite): ?>
    <div class="card mb-2 invite-card">
        <div class="card-body pt-3 pb-3">
            <div class="row">
                <div class="col-1 d-none">
                    <span><i val="<?= $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/'; ?>"
                             class="far fa-copy copy-link" data-toggle="tooltip" data-placement="bottom"
                             title="<?= $GLOBALS['_copyinvite'] ?>"></i>
                    </span>
                </div>
                <div class="col-4">
                    <?= $invite['email'] ?>
                </div>
                <div class="col-4">
                    <div class="invite-date">
                        <?= date('d.m.Y', $invite['invite_date']) ?>
                    </div>
                </div>
                <?php if ($invite['status']): ?>
                    <div class="col-4">
                        <a href="/../profile/<?= $invite['status']; ?>"><?= $GLOBALS['_registeredinvite'] ?></a>
                    </div>
                <?php else: ?>
                    <div class="col-4">
                        <span><?= $GLOBALS['_sentinvite'] ?></span>
                        <a href="#" class="invite-cancel" data-invite-id="<?= $invite['invite_id'] ?>"><i
                                    class="far fa-times-circle invite-delete"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
endforeach;
?>

