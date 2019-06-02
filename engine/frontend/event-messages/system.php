<li data-event-id="<?=$event['event_id']?>" class="event <?= ($event['view_status'])? '' : 'new-event' ?> <?= ($event['action'] == 'comment')? 'comment' : 'task'; ?>">

    <?php if ($event['action'] == 'newUserRegistered'): // новый пользователь ?>
        <?php
            $bg = 'bg-success';
            $icon = 'fas fa-user';
            $action = $GLOBALS['_newUserRegistered'] . ' <a href="/../' . $event["link"] .'">' . $event['name'] . ' ' .$event['surname'] . '</a>';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'newCompanyRegistered'): // регистрация компании ?>
        <?php
            $bg = 'bg-success';
            $icon = 'fas fa-exclamation';
            $action = $GLOBALS['_newCompanyRegistered'];
        ?>
    <?php endif; ?>

    <span class="before <?=$bg?>"><i class="<?=$icon?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m i:s", strtotime($event['datetime'])); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <span class="font-weight-bold">Lusy</span>
    </div>
    <p class="mt-2"><?= $action ?></p>
</li>

</li>

