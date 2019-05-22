<div data-event-id="<?=$event['event_id']?>" class="card card-body m-1 event <?= ($event['view_status'])? '' : 'new-event' ?> <?= ($event['action'] == 'comment')? 'comment' : 'task'; ?>">
    <?=$event['action']?> <?=$event['name']?> <?=$event['surname']?> <?=$event['datetime']?>
    <a href="/../<?= $event['link'] ?>">Перейти</a>
</div>