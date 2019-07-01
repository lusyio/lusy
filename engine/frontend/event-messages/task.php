<li data-event-id="<?= $event['event_id'] ?>"
     class="event <?= ($event['view_status']) ? '' : 'new-event' ?> <?= ($event['action'] == 'createtask') ? '' : 'readable-here' ?> task mb-3">

    <?php
    $taskLink = '<a href="/../' . $event['link'] . '">' . $event['taskname'] . '</a>';

    if ($event['action'] == 'createtask') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'fas fa-plus';
        if ($event['author_id'] == '1') {

            if ($isSelfTask) {
                $eventText = $GLOBALS['_youCreatedSelfTask'] . ' ';
                $eventText .= $taskLink . '. ';
            } else {
                $eventText = $GLOBALS['_youCreatedTask'] . ' ';
                $eventText .= $taskLink . '. ';
                $eventText .= $GLOBALS['_responsible'] . ' <span>' . $event['workerName'] . ' ' . $event['workerSurname'] . '</span>. ';
            }
            $eventText .= $GLOBALS['_periodOfExecutionUntil'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        } else {
            $eventText = $GLOBALS['_assignedYouTask'] . ' - ' . $taskLink;

        }
    }
    if ($event['action'] == 'createinittask') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'fas fa-plus';
        if ($event['author_id'] == '1') {

            $eventText = $GLOBALS['_assignedYouTask'] . ' - ' . $taskLink;

        }
    }

    if ($event['action'] == 'viewtask') {
        $bg = 'bg-primary';
        $icon = 'fas fa-plus';
        $eventText = $GLOBALS['_sawTheTask'] . ' ';
        $eventText .= $taskLink;

    }

    if ($event['action'] == 'canceltask') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'fas fa-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youCanceledTheTask'] . ' - ';
            $eventText .= $taskLink;
        } else {
            $eventText = $GLOBALS['_taskCanceled'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'overdue') {
        $bg = 'bg-danger';
        $icon = 'fas fa-plus';
        $eventText = $GLOBALS['_taskOverdue'] . ' ';
        $eventText .= $taskLink;
    }

    if ($event['action'] == 'review') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'fas fa-file-import';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youSentOnReview'] . ' - ';
            $eventText .= $taskLink;
        } else {
            $eventText = $GLOBALS['_youReceivedTaskReport'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'workreturn') { // создание, назначение задачи
        $bg = 'bg-warning';
        $icon = 'fas fa-exchange-alt';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youReturnedTask'] . ' - ';
            $eventText .= $taskLink . '. ';
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        } else {
            $eventText = $GLOBALS['_taskReturnedToYou'] . ' - ';
            $eventText .= $taskLink;

        }
    }

    if ($event['action'] == 'workdone') { // создание, назначение задачи
        $bg = 'bg-success';
        $icon = 'fas fa-check';
        if ($event['worker'] == $id && !$isSelfTask) {
            $eventText = $GLOBALS['_workDoneWorker'] . ' - ';
            $eventText .= $taskLink;
        } else {
            $eventText = $GLOBALS['_workDoneManager'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'postpone') { // создание, назначение задачи
        $bg = 'bg-warning';
        $icon = 'far fa-calendar-alt';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youAskedToPostpone'] . ' - ';
            $eventText .= $taskLink;
        } else {
            $eventText = $GLOBALS['_workerAskedToPostpone'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'confirmdate') { // создание, назначение задачи
        $bg = 'bg-success';
        $icon = 'fas fa-check';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youConfirmPostponeAsk'] . ' - ';
            $eventText .= $taskLink . '. ';
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        } else {
            $eventText = $GLOBALS['_confirmYourPostponeAsk'] . ' - ';
            $eventText .= $taskLink;
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        }
    }

    if ($event['action'] == 'canceldate') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'far fa-calendar-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youDeclinePostponeAsk'] . ' - ';
            $eventText .= $taskLink;
        } else {
            $eventText = $GLOBALS['_declineYourPostponeAsk'] . ' - ';
            $eventText .= $taskLink;

        }
    }

    if ($event['action'] == 'changeworker') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'far fa-calendar-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youChangedWorker'] . ' - ';
            $eventText .= $taskLink . '. ';
            $eventText .= $GLOBALS['_responsible'] . ' ' . $event['workerName'] . ' ' . $event['workerSurname'] . '. ';
        } else {
            $eventText = $GLOBALS['_youAreSuspendedFromTheTask'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'senddate') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youSetNewDateInTask'] . ' - ';
            $eventText .= $taskLink . '. ';
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        } else {
            $eventText = $GLOBALS['_newDateInYourTask'] . ' - ';
            $eventText .= $taskLink;
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
        }
    }

    if ($event['action'] == 'addcoworker') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
            $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
            $eventText = $GLOBALS['_youAddCoworker'] . ' - ';
            $eventText .= $taskLink;
            $eventText .= $GLOBALS['_newCoworker'] . ' - ' . $coworkerName . ' ' . $coworkerSurname;
        } else {
            $eventText = $GLOBALS['_youAreNewCoworker'] . ' - ';
            $eventText .= $taskLink;
        }
    }

    if ($event['action'] == 'removecoworker') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
            $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
            $eventText = $GLOBALS['_youRemoveCoworker'] . ' - ';
            $eventText .= $taskLink;
            $eventText .= $GLOBALS['_removedCoworker'] . ' - ' . $coworkerName . ' ' . $coworkerSurname;
        } else {
            $eventText = $GLOBALS['_youAreNotCoworker'] . ' - ';
            $eventText .= $taskLink;
        }
    }
?>
    <span class="before <?=$bg?>"><i class="<?=$icon?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m H:i", $event['datetime']); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <?php if ($event['author_id'] == 1): ?>
            <a class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
        <?php else: ?>
            <a href="/profile/<?=$event['author_id']?>/" class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
        <?php endif; ?>
    </div>
    <p class="eventText"><?= $eventText ?></p>
</li>


