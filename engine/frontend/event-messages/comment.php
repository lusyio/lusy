<?php
$month = ['', _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December")];
$monthNumber = date("n", $event['datetime']);
$eventText = _('Wrote comment');
?>
<a href="/../<?= $event['link'] ?>" class="text-decoration-none text-dark">
    <li data-event-id="<?= $event['event_id'] ?>"
        class="event <?= ($event['view_status']) ? '' : 'new-event' ?> comment readable-here mb-3">
        <div class="eventDiv">
            <div class="row">
                <div class="col-2">
                    <div class="text-right float-right">
                        <p class="mb-0 font-weight-bold"><?= date("d", $event['datetime']); ?> <span
                                    class="text-lowercase"><?= _($month[$monthNumber]) ?></span></p>
                        <span class="text-secondary">в <?= date("H:i", $event['datetime']); ?></span>
                    </div>
                </div>
                <div class="col-1">
                    <div class="bg-secondary logIcon">
                        <i class="fas fa-comment"></i>
                    </div>
                </div>
                <div class="col-5">
                    <div class="pl-2">
                        <p class="mb-0 text-area-message font-weight-bold"><?= $event['taskname']; ?></p>
                        <div class="text-area-message">
                            <span class="text-secondary"><?= (is_null($event['commentText'])) ? 'Комментарий удалён' : $event['commentText']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="float-right statusText font-weight-bold text-right">
                        <?= $eventText ?><br>
                        <span class="text-secondary text-capitalize font-weight-normal"><?= $event['name'] ?> <?= $event['surname'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </li>
</a>