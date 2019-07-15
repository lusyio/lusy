<a href="/../<?= $event['link'] ?>" class="text-decoration-none text-dark">
    <li data-event-id="<?= $event['event_id'] ?>"
        class="event <?= ($event['view_status']) ? '' : 'new-event' ?> <?= ($event['action'] == 'createtask') ? '' : 'readable-here' ?> task mb-3">

        <?php
        $taskLink = '<a href="/../' . $event['link'] . '">' . $event['taskname'] . '</a>';
        $eventDop = '';
        if ($event['action'] == 'createtask') { // создание, назначение задачи
            $bg = 'primary';
            $icon = 'fas fa-plus';
            if ($event['author_id'] == '1') {

                if ($isSelfTask) {
                    $eventText = _('You create the task');
                    $eventDop = _('for yourself');
                } else {
                    $eventText = _('You create the task');
                    $eventDop = _('for') . ' <span class="text-capitalize">' . $event['workerName'] . ' ' . $event['workerSurname'] . '</span>';
                }
//            $eventText .= $GLOBALS['_periodOfExecutionUntil'] . ' <span>' . date('d.m', $event['comment']) . '</span>';

            } else {
                $eventText = $GLOBALS['_assignedYouTask'] . ' - ' . $taskLink;
                $eventText = _('You have a task assigned');
                $eventDop = _('Deadline to') . ' ' . date('d.m', $event['comment']);
            }
        }
        if ($event['action'] == 'createplantask') { // создание, назначение задачи
            $bg = 'warning';
            $icon = 'fas fa-plus';
            if ($event['author_id'] == '1') {
                $eventText = $GLOBALS['_youCreatedPlanTask'] . ' ';
                $eventText .= $GLOBALS['_responsible'] . ' <span>' . $event['workerName'] . ' ' . $event['workerSurname'] . '</span>. ';
                $eventText .= $GLOBALS['_planDate'] . ' <span>' . date('d.m', $event['comment']) . '</span>';
                $eventText = _('You have scheduled a task');
                $eventDop = _('Task will start') . ' ' . date('d.m', $event['comment']);
            }

        }
        if ($event['action'] == 'createinittask') { // создание первой задачи от Люси
            $bg = 'primary';
            $icon = 'fas fa-plus';
            if ($event['author_id'] == '1') {

                $eventText = _('You have a task assigned');
                $eventDop = _('Deadline to') . ' ' . date('d.m', $event['comment']);
            }
        }

        if ($event['action'] == 'viewtask') { // ознакомился
            $bg = 'primary';
            $icon = 'far fa-eye';
            $eventText = _('Saw the task');
        }

        if ($event['action'] == 'canceltask') { // отменена
            $bg = 'danger';
            $icon = 'fas fa-times';
            if ($event['author_id'] == '1') {
                $eventText = _('You canceled the task');
            } else {
                $eventText = _('Task canceled');
            }
        }

        if ($event['action'] == 'overdue') { // просрочена
            $bg = 'danger';
            $icon = 'fab fa-gripfire';
            $eventText = _('Task overdue');
            $eventDop = _('Take action');
        }

        if ($event['action'] == 'review') { // отправлена на рассмотрение
            $bg = 'primary';
            $icon = 'fas fa-file-import';
            if ($event['author_id'] == '1') {
                $eventText = _('Task completed');
                $eventDop = _('Expect approval');
            } else {
                $eventText = _('Task completed');
                $eventDop = _('Read the report');
            }
        }

        if ($event['action'] == 'workreturn') { // возвращена в работу
            $bg = 'warning';
            $icon = 'fas fa-exchange-alt';
            if ($event['author_id'] == '1') {
                $eventText = _('You returned task');
                $eventDop = _('New deadline') . ' ' . _('To the') . ' ' . date('d.m', $event['comment']);
            } else {
                $eventText = $GLOBALS['_taskReturnedToYou'] . ' - ';
                $eventText .= $taskLink;
                $eventText = _('Task returned to you');
                $eventDop = _('New deadline') . ' ' . _('To the') . ' ' . date('d.m', $event['comment']);
            }
        }

        if ($event['action'] == 'workdone') { // задача завершена
            $bg = 'success';
            $icon = 'fas fa-check';
            if ($event['worker'] == $id && !$isSelfTask) {
                $eventText = _('Task done');
            } else {
                $eventText = _('Task done');
            }
        }

        if ($event['action'] == 'postpone') { // перенос срока
            $bg = 'warning';
            $icon = 'far fa-calendar-alt';
            if ($event['author_id'] == '1') {
                $eventText = _('You requested a postponement');
                $eventDop = _('to') . ' ' . date('d.m', $event['comment']);
            } else {
                $eventText = _('Request for postponement');
                $eventDop = $eventDop = _('to') . ' ' . date('d.m', $event['comment']);
            }
        }

        if ($event['action'] == 'confirmdate') { // одобрение переноса срока
            $bg = 'success';
            $icon = 'fas fa-check';
            if ($event['author_id'] == '1') {
                $eventText = _('You confirm postpone');
                $eventDop = _('to') . ' ' . date('d.m', $event['comment']);
            } else {
                $eventText = _('You have approved postpone');
                $eventDop = _('to') . ' ' . date('d.m', $event['comment']);
            }
        }

        if ($event['action'] == 'canceldate') { // не одобрили перенос срока
            $bg = 'danger';
            $icon = 'far fa-calendar-times';
            if ($event['author_id'] == '1') {
                $eventText = _('You decline postpone');
                $eventDop = _('Current deadline') . ' ' . date('d.m', $event['comment']);
            } else {
                $eventText = _('Decline your postpone');
                $eventDop = _('Current deadline') . ' ' . date('d.m', $event['comment']);
            }
        }

        if ($event['action'] == 'changeworker') { // смена ответственного
            $bg = 'danger';
            $icon = 'far fa-calendar-times';
            if ($event['author_id'] == '1') {
                $eventText = _('You changed worker');
                $eventDop = _('New worker is') . ' <span class="text-capitalize">' . $event['workerName'] . ' ' . $event['workerSurname'] . '</span>';
            } else {
                $eventText = _('Task reassigned');
            }
        }

        if ($event['action'] == 'senddate') { // назначен новый срок
            $bg = 'primary';
            $icon = 'far fa-calendar-plus';
            if ($event['author_id'] == '1') {
                $eventText = _('You edit deadline');
                $eventDop = _('To the') . ' ' . date('d.m', $event['comment']);

            } else {
                $eventText = _('New deadline');
                $eventDop = _('To the') . ' ' . date('d.m', $event['comment']);

            }
        }

        if ($event['action'] == 'addcoworker') { // добавился соисполнитель
            $bg = 'primary';
            $icon = 'far fa-calendar-plus';
            if ($event['author_id'] == '1') {
                $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
                $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
                $eventText = _('You add coworker');
                $eventDop = _('New coworker is') . ' <span class="text-capitalize">' . $coworkerName . ' ' . $coworkerSurname . '</span>';
            } else {
                $eventText = _('You are new coworker');
            }
        }

        if ($event['action'] == 'removecoworker') { // удалил соисполнителя
            $bg = 'primary';
            $icon = 'far fa-calendar-plus';
            if ($event['author_id'] == '1') {
                $coworkerName = DBOnce('name', 'users', 'id=' . $event['comment']);
                $coworkerSurname = DBOnce('surname', 'users', 'id=' . $event['comment']);
                $eventText = _('You remove coworker');
                $eventDop = _('It was') . ' <span class="text-capitalize">' . $coworkerName . ' ' . $coworkerSurname . '</span>';
            } else {
                $eventText = _('Task reassigned');
            }
        }
        $month = ['', _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December")];
        $monthNumber = date("n", $event['datetime']);
        ?>

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
                    <div class="bg-<?= $bg ?> logIcon">
                        <i class="<?= $icon ?> "></i>
                    </div>
                </div>
                <div class="col-4">
                    <p class="mb-0 font-weight-bold"><?= $event['taskname'] ?></p>
                    <div>
                        <?php if ($event['author_id'] == 1): ?>
                            <span class="text-secondary"><?= _('System message') ?></span>
                        <?php else: ?>
                            <span href="/profile/<?= $event['author_id'] ?>/"
                                  class="text-secondary"><?= $event['name'] ?> <?= $event['surname'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-5">
                    <div class="float-right statusText font-weight-bold text-right text-<?= $bg ?>">
                        <?= $eventText ?><br>
                        <span class="text-secondary text-lowercase font-weight-normal"><?= $eventDop ?></span>
                    </div>
                </div>
            </div>
    </li>
</a>



