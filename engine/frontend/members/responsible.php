<div class="responsible">
    <div class="responsible-card">
        <?php
        foreach ($users as $n) { ?>
            <div val="<?php echo $n['id'] ?>" class="select-responsible <?=(isset($taskEdit) && $taskEdit && (in_array($n['id'], $taskCoworkers) || $n['id'] == $worker || $n['id'] == $manager)) ? 'd-none' : ''?>">
                <div class="row">
                    <div class="col-2">
                        <img src="/<?= getAvatarLink($n["id"]) ?>" class="avatar-added mr-1">
                    </div>
                    <div class="col text-left pr-0">
                        <span class="mb-1 add-coworker-text"><?= (trim($n['name'] . ' ' . $n['surname']) == '') ? $n['email'] : trim($n['name'] . ' ' . $n['surname']) ?></span>
                    </div>
                    <div class="col-2 pl-0">
                        <i class="fas fa-exchange-alt icon-change-responsible"></i>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>