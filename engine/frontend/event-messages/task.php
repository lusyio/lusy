<li data-event-id="<?= $event['event_id'] ?>"
     class="event <?= ($event['view_status']) ? '' : 'new-event' ?> task">

    <?php if ($event['action'] == 'createtask'): // создание, назначение задачи ?>
        <?php $action = $GLOBALS['_logNewTask']; ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'senddate'): // назначение нового срока ?>
        <?php $action = $GLOBALS['_logNewDate']; ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'canceldate'): // запрос на перенос срока отклонен ?>
        <?php $action = $GLOBALS['_logCancelDate']; ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'sendonreview'): // отправлен отчет о выполнении ?>
        <?php $action = $GLOBALS['_logSendOnReview']; ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workreturn'): // возврат на доработку ?>
        <?php $action = $GLOBALS['_logWorkReturn']; ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workdone'): // задача завершена?>
        <?php $action = $GLOBALS['_logWorkDone']; ?>
    <?php endif; ?>
    <span class="before bg-danger"><i class="fas fa-exclamation"></i></span>
    <div class="position-relative">
        <span class="date"><?= $event['datetime'] ?></span>
        <img src="/upload/avatar/2.jpg" class="avatar mr-1">
        <a href="/profile/2/" class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
    </div>
    <p class="mt-2"><?= $action ?>
        <a href="/../<?= $event['link'] ?>">Перейти</a></p>
</li>

