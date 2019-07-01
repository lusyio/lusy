<div class="coworkers">
    <div class="coworker-card">
        <div class="empty-list text-muted">
            Список пуст
        </div>
        <?php
        $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"] . ' AND is_fired = 0');
        foreach ($users as $n) { ?>
            <div val="<?php echo $n['id'] ?>" class="select-coworker">
                <div class="row">
                    <div class="col-1">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                    </div>
                    <div class="col text-left">
                        <span class="mb-1 add-coworker-text"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname'])?></span>
                    </div>
                    <div class="col-2">
                        <i class="fas fa-plus icon-add-coworker"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>