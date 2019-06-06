<li data-event-id="<?= $event['event_id'] ?>"
     class="event <?= ($event['view_status']) ? '' : 'new-event' ?> <?= ($event['action'] == 'createtask') ? '' : 'readable-here' ?> task mb-3">

    <?php
    if ($event['action'] == 'createtask') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'fas fa-plus';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youCreatedTask'] . ' ' . $event['taskname'] . '. ';
            $eventText .= $GLOBALS['_responsible'] . ' ' . $event['workerName'] . ' ' . $event['workerSurname'] . '. ';
            $eventText .= $GLOBALS['_periodOfExecutionUntil'] . ' ' . date('d.m', $event['datedone']);
        } else {
            $eventText = $GLOBALS['_assignedYouTask'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'viewtask') {
        $bg = 'bg-primary';
        $icon = 'fas fa-plus';
        $eventText = $GLOBALS['_sawTheTask'] . ' ' . $event['taskname'] . '.';
    }

    if ($event['action'] == 'canceltask') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'fas fa-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youCanceledTheTask'] . ' - ' . $event['taskname'];
        } else {
            $eventText = $GLOBALS['_taskCanceled'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'overdue') {
        $bg = 'bg-danger';
        $icon = 'fas fa-plus';
        $eventText = $GLOBALS['_taskOverdue'] . ' ' . $event['taskname'];
    }

    if ($event['action'] == 'review') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'fas fa-file-import';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youSentOnReview'] . ' - ' . $event['taskname'];
        } else {
            $eventText = $GLOBALS['_youReceivedTaskReport'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'workreturn') { // создание, назначение задачи
        $bg = 'bg-warning';
        $icon = 'fas fa-exchange-alt';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youReturnedTask'] . ' - ' . $event['taskname'] . '. ';
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' ' . date('d.m', $event['datepostpone']);
        } else {
            $eventText = $GLOBALS['_taskReturnedToYou'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'workdone') { // создание, назначение задачи
        $bg = 'bg-success';
        $icon = 'fas fa-check';
        if ($event['worker'] == $id) {
            $eventText = $GLOBALS['_workDoneWorker'];
        } else {
            $eventText = $GLOBALS['_workDoneManager'];
        }
    }

    if ($event['action'] == 'postpone') { // создание, назначение задачи
        $bg = 'bg-warning';
        $icon = 'far fa-calendar-alt';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youAskedToPostpone'] . ' - ' . $event['taskname'];
        } else {
            $eventText = $GLOBALS['_workerAskedToPostpone'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'confirmdate') { // создание, назначение задачи
        $bg = 'bg-success';
        $icon = 'fas fa-check';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youConfirmPostponeAsk'] . ' - ' . $event['taskname'] . '. ';
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' ' . date('d.m', $event['datepostpone']);
        } else {
            $eventText = $GLOBALS['_confirmYourPostponeAsk'] . ' - ' . $event['taskname'];
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' ' . date('d.m', $event['datepostpone']);
        }
    }

    if ($event['action'] == 'canceldate') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'far fa-calendar-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youDeclinePostponeAsk'] . ' - ' . $event['taskname'];
        } else {
            $eventText = $GLOBALS['_declineYourPostponeAsk'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'changeworker') { // создание, назначение задачи
        $bg = 'bg-danger';
        $icon = 'far fa-calendar-times';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youChangedWorker'] . ' - ' . $event['taskname'] . '. ';
            $eventText .= $GLOBALS['_responsible'] . ' ' . $event['workerName'] . ' ' . $event['workerSurname'] . '. ';
        } else {
            $eventText = $GLOBALS['_youAreSuspendedFromTheTask'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'senddate') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $eventText = $GLOBALS['_youSetNewDateInTask'] . ' - ' . $event['taskname'];
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' ' . date('d.m', $event['datepostpone']);
        } else {
            $eventText = $GLOBALS['_newDateInYourTask'] . ' - ' . $event['taskname'];
            $eventText .= $GLOBALS['_taskNewDeadline'] . ' ' . date('d.m', $event['datepostpone']);
        }
    }

    if ($event['action'] == 'addcoworker') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
            $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
            $eventText = $GLOBALS['_youAddCoworker'] . ' - ' . $event['taskname'];
            $eventText .= $GLOBALS['_newCoworker'] . ' - ' . $coworkerName . ' ' . $coworkerSurname;
        } else {
            $eventText = $GLOBALS['_youAreNewCoworker'] . ' - ' . $event['taskname'];
        }
    }

    if ($event['action'] == 'removecoworker') { // создание, назначение задачи
        $bg = 'bg-primary';
        $icon = 'far fa-calendar-plus';
        if ($event['author_id'] == '1') {
            $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
            $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
            $eventText = $GLOBALS['_youRemoveCoworker'] . ' - ' . $event['taskname'];
            $eventText .= $GLOBALS['_removedCoworker'] . ' - ' . $coworkerName . ' ' . $coworkerSurname;
        } else {
            $eventText = $GLOBALS['_youAreNotCoworker'] . ' - ' . $event['taskname'];
        }
    }
?>
    <span class="before <?=$bg?>"><i class="<?=$icon?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m H:i", $event['datetime']); ?></span>
        <img src="/<?=getAvatarLink($event['author_id'])?>" class="avatar mr-2">
        <a href="/profile/<?=$event['author_id']?>/" class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></a>
    </div>
    <p class="mt-2"><?= $eventText ?>
        <a href="/../<?= $event['link'] ?>" class="font-italic task-link">"<?=$event['taskname']?>"</a></p>
</li>


