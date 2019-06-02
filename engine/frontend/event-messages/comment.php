<li data-event-id="<?= $event['event_id'] ?>"
     class="event <?= ($event['view_status']) ? '' : 'new-event' ?> comment">


    <span class="before bg-secondary"><i class="fas fa-comment"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m H:i", strtotime($event['datetime'])); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <a href="/profile/<?=$event['author_id']?>/" class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
    </div>
    <p class="mt-2 mb-2"><?= $GLOBALS['_logNewComment'] ?> <a href="/../<?= $event['link'] ?>" class="font-italic">"<?=$event['taskname']?>"</a></p>
        <div class="commentText p-2 pl-3"><?= $event['commentText'] ?></div>

</li>