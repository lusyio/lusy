<div class="coworkers">
    <div class="coworker-card">
        <div class="empty-list text-muted">
            Список пуст
        </div>
        <?php foreach ($users as $n) { ?>
            <?php if ($n['id'] == $id) {
                continue;
            } ?>
            <div val="<?php echo $n['id'] ?>" class="select-coworker <?=($taskEdit && (in_array($n['id'], $taskCoworkers) || $n['id'] == $worker || $n['id'] == $manager)) ? 'd-none' : ''?>">
                <div class="row">
                    <div class="col-2">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                    </div>
                    <div class="col text-left pr-0">
                        <span class="mb-1 add-coworker-text"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname'])?></span>
                    </div>
                    <div class="col-2 pl-0">
                        <i class="fas fa-plus icon-add-coworker"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>