<div data-event-id="<?=$event['event_id']?>" class="card card-body m-1 event <?= ($event['view_status'])? '' : 'new-event' ?> <?= ($event['action'] == 'comment')? 'comment' : 'task'; ?>">

    <?php if ($event['action'] == 'newUserRegistered'): // создание, назначение задачи ?>
        <?= $GLOBALS['_newUserRegistered'] ?> <?= $event['name'] ?> <?= $event['surname'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>

    <a href="/../<?= $event['link'] ?>">Перейти</a>
</div>

