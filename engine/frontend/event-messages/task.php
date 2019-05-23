<div data-event-id="<?= $event['event_id'] ?>"
     class="card card-body m-1 event <?= ($event['view_status']) ? '' : 'new-event' ?> task">

    <?php if ($event['action'] == 'createtask'): // создание, назначение задачи ?>
        <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logNewTask'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'senddate'): // назначение нового срока ?>
        <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logNewDate'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'canceldate'): // запрос на перенос срока отклонен ?>
        <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logCancelDate'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'sendonreview'): // отправлен отчет о выполнении ?>
        <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logSendOnReview'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workreturn'): // возврат на доработку ?>
        <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logWorkReturn'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <?php if ($event['action'] == 'workdone'): // задача завершена?>
        <?= $GLOBALS['_logWorkDone'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <a href="/../<?= $event['link'] ?>">Перейти</a>
</div>

