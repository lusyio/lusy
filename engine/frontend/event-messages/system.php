<div data-event-id="<?=$event['event_id']?>" class="card card-body m-1 event <?= ($event['view_status'])? '' : 'new-event' ?> <?= ($event['action'] == 'comment')? 'comment' : 'task'; ?>">

    <?php if ($event['action'] == 'newUserRegistered'): // создание, назначение задачи ?>
        <?= $GLOBALS['_newUserRegistered'] ?> <?= $event['name'] ?> <?= $event['surname'] ?> <?= $event['datetime'] ?>
        <a href="/../<?= $event['link'] ?>">Перейти</a>
    <?php endif; ?>

    <?php if ($event['action'] == 'newCompanyRegistered'): // регистрация компании ?>
        <?= $GLOBALS['_newCompanyRegistered'] ?> <?= $event['datetime'] ?>
    <?php endif; ?>


</div>

