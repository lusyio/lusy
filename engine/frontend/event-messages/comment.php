<div data-event-id="<?= $event['event_id'] ?>"
     class="card card-body m-1 event <?= ($event['view_status']) ? '' : 'new-event' ?> comment">
    <?= $event['name'] ?> <?= $event['surname'] ?> <?= $GLOBALS['_logNewComment'] ?>: "<?= $event['commentText'] ?>" <?= $event['datetime'] ?>
    <a href="/../<?= $event['link'] ?>">Перейти</a>
</div>