<div data-event-id="<?= $event['event_id'] ?>"
     class="card card-body m-1 event <?= ($event['view_status']) ? '' : 'new-event' ?> comment">
    <?= $event['action'] ?> <?= $event['commentText'] ?> <?= $event['name'] ?> <?= $event['surname'] ?> <?= $event['datetime'] ?>
    <a href="/../<?= $event['link'] ?>">Перейти</a>
</div>