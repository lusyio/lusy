<li data-event-id="<?= $event['event_id'] ?>"
     class="event <?= ($event['view_status']) ? '' : 'new-event' ?> task mb-3">

    <?php if ($event['action'] == 'createtask'): // создание, назначение задачи ?>
        <?php
            $action = $GLOBALS['_logNewTask'];
            $bg = 'bg-primary';
            $icon = 'fas fa-plus';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'senddate'): // назначение нового срока ?>
        <?php
            $action = $GLOBALS['_logNewDate'];
            $bg = 'bg-primary';
            $icon = 'far fa-calendar-plus';
        ?>
    <?php endif; ?>


    <?php if ($event['action'] == 'postpone'): // запрос на перенос срока отправлен ?>
        <?php
        $action = $GLOBALS['_logPostPone'];
        $bg = 'bg-warning';
        $icon = 'far fa-calendar-alt';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'confirmdate'): // запрос на перенос срока утвержден ?>
        <?php
        $action = $GLOBALS['_logConfirmDate'];
        $bg = 'bg-success';
        $icon = 'fas fa-check';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'canceltask'): // задача отменена ?>
        <?php
        $action = $GLOBALS['_logCancelTask'];
        $bg = 'bg-danger';
        $icon = 'fas fa-times';
        ?>
    <?php endif; ?>
    <?php if ($event['action'] == 'canceldate'): // запрос на перенос срока отклонен ?>
        <?php
            $action = $GLOBALS['_logCancelDate'];
            $bg = 'bg-danger';
            $icon = 'far fa-calendar-times';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'review'): // отправлен отчет о выполнении ?>
        <?php
            $action = $GLOBALS['_logSendOnReview'];
            $bg = 'bg-primary';
            $icon = 'fas fa-file-import';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workreturn'): // возврат на доработку ?>
        <?php
            $action = $GLOBALS['_logWorkReturn'];
            $bg = 'bg-warning';
            $icon = 'fas fa-exchange-alt';
        ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workdone'): // задача завершена?>
        <?php
            $action = $GLOBALS['_logWorkDone'];
            $bg = 'bg-success';
            $icon = 'fas fa-check';
        ?>
    <?php endif; ?>
    <span class="before <?=$bg?>"><i class="<?=$icon?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m i:s", strtotime($event['datetime'])); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <a href="/profile/<?=$event['author_id']?>/" class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
    </div>
    <p class="mt-2"><?= $action ?>
        <a href="/../<?= $event['link'] ?>" class="font-italic">"<?=$event['taskname']?>"</a></p>
</li>

