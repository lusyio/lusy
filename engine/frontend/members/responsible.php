<div class="responsible">
    <div class="responsible-card">
        <?php
        $users = DB('*', 'users', 'idcompany=' . $GLOBALS["idc"]);
        foreach ($users as $n) { ?>
            <div val="<?php echo $n['id'] ?>" class="select-responsible">
                <div class="row">
                    <div class="col-1">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                    </div>
                    <div class="col text-left">
                        <span class="mb-1 add-coworker-text"><?php echo $n['name'] . ' ' . $n['surname'] ?></span>
                    </div>
                    <div class="col-2">
                        <i class="fas fa-exchange-alt icon-change-responsible"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>