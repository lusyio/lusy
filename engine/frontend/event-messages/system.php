<li data-event-id="<?=$event['event_id']?>" class="event <?= ($event['view_status'])? '' : 'new-event' ?> system readable-here">

    <?php if ($event['action'] == 'newuser'): // новый пользователь ?>
        <?php
            $bg = 'bg-success';
            $icon = 'fas fa-user';
            $action = $GLOBALS['_newUserRegistered'] . ' <a href="/../' . $event["comment"] .'">' . $event['name'] . ' ' .$event['surname'] . '</a>';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'newcompany'): // регистрация компании ?>
        <?php
            $bg = 'bg-success';
            $icon = 'fas fa-exclamation';
            $action = $GLOBALS['_newCompanyRegistered'];
        ?>
    <?php endif; ?>

    <span class="before <?=$bg?>"><i class="<?=$icon?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m H:i", $event['datetime']); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <span class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></span>
    </div>
    <p class="mt-2"><?= $action ?></p>
</li>

</li>

