<?php
$invitesQuery = $pdo->prepare('SELECT invite_id, code, invite_date, status, email, invitee_position FROM invitations WHERE company_id=:companyId ORDER BY invite_date DESC');
$invitesQuery->execute(array(':companyId' => $idc));
$invites = $invitesQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<?php foreach ($invites as $invite): ?>
    <div class="card mb-2 invite-card">
        <div class="card-body pt-3 pb-3">
            <div class="row">
                <div class="col-1 d-none">
                    <span><i val="<?= 'https://' . $_SERVER['HTTP_HOST'] . '/join/' . $invite['code'] . '/'; ?>"
                             class="far fa-copy copy-link" data-toggle="tooltip" data-placement="bottom"
                             title="<?= $GLOBALS['_copyinvite'] ?>"></i>
                    </span>
                </div>
                <div class="col-12 col-lg-4 invite-card-content">
                    <?= $invite['email'] ?>
                </div>
                <div class="col-6 col-lg-4">
                    <div class="invite-date invite-card-text">
                        <?= date('d.m.Y', $invite['invite_date']) ?>
                    </div>
                </div>
                <?php if ($invite['status']): ?>
                    <div class="col-6 col-lg-4 invite-card-text">
                        <a href="/../profile/<?= $invite['status']; ?>/"><?= $GLOBALS['_registeredinvite'] ?></a>
                    </div>
                <?php else: ?>
                    <div class="col-6 col-lg-4 invite-card-text">
                        <span><?= $GLOBALS['_sentinvite'] ?></span>
                        <a data-toggle="tooltip" data-placement="bottom" title="Отменить приглашение" href="#"
                           class="invite-card-text invite-cancel" data-invite-id="<?= $invite['invite_id'] ?>"><i
                                    class="far fa-times-circle invite-delete"></i></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
endforeach;
?>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>