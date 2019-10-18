<div class="responsible" self-id="<?= $id ?>">
    <div class="responsible-card">
        <?php
        foreach ($users as $n) { ?>
            <?php if (((isset($taskEdit) && !$taskEdit) || !isset($taskEdit)) && count($users) == 1 && !isset($reportPage)): ?>
            <div val="<?php echo $n['id'] ?>" class="select-responsible<?= ($n['id'] == $id) ? ' self-user' : '' ?> d-none">
            <?php else: ?>
            <div val="<?php echo $n['id'] ?>" class="select-responsible<?= ($n['id'] == $id) ? ' self-user' : '' ?> <?=(isset($taskEdit) && $taskEdit && (in_array($n['id'], $taskCoworkers) || $n['id'] == $worker || $n['id'] == $manager)) ? 'd-none' : ''?>">
            <?php endif; ?>
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
