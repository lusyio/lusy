<?php
require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';
?>
<?php
if ($event['action'] == 'newuser') { // новый пользователь
    $bg = 'success';
    $icon = 'fas fa-user';
    $action = 'Новый сотрудник';
    $event['link'] = $event["comment"] . '/';
    $eventText = fiomess($event["comment"]);
}
if ($event['action'] == 'newcompany') { // регистрация компании
    $bg = 'danger';
    $icon = 'fas fa-heart';
    $eventText = 'Добро пожаловать!';
    $eventDop = 'Ваша компания зарегистрирована';
    $action = '';
    $event['link'] = 'company/';
}
if ($event['action'] == 'newachievement') { // новое достижение
    $badges = [
        'meeting' => 'fas fa-handshake',
        'invitor' => 'fas fa-user-plus',
        'bugReport_1' => 'fab fa-accessible-icon',
        'message_1' => 'fas fa-broadcast-tower',
        'taskOverdue_1' => 'fas fa-meh',
        'taskPostpone_1' => 'fas fa-crown',
        'taskDoneWithCoworker_1' => 'fas fa-user-friends',
        'selfTask_1' => 'fas fa-user-tie',
        'taskDone_1' => 'fas fa-thumbs-up',
        'taskDone_10' => 'fas fa-star',
        'taskDone_50' => 'fas fa-star',
        'taskDone_100' => 'fas fa-star',
        'taskDone_200' => 'fas fa-star',
        'taskDone_500' => 'fas fa-star',
        'taskDonePerMonth_500' => 'fas fa-user-graduate',
        'taskCreate_10' => 'fas fa-atom',
        'taskCreate_50' => 'fas fa-atom',
        'taskCreate_100' => 'fas fa-atom',
        'taskCreate_200' => 'fas fa-atom',
        'taskCreate_500' => 'fas fa-atom',
        'comment_1000' => 'fas fa-comment',
        'taskOverduePerMonth_0' => 'fas fa-medal',
        'taskDonePerMonth_leader' => 'fas fa-greater-than',
        'taskInwork_20' => 'fas fa-brain',
        'taskCreatePerDay_30' => 'fas fa-bolt',
    ];
    $bg = 'primary';
    $icon = 'fas !иконкаАчивки!';
    $action = $GLOBALS['_youGotNewAchievement'];
    $eventText = $GLOBALS['_' . $event['comment']];
    $event['link'] = 'awards/';
}
if ($event['action'] == 'birthday') { // день рождения
    $bg = 'success';
    $icon = 'fas fa-birthday-cake';
    $eventText = 'День рождения!';
    $eventDop = 'у ' . fiomess($event["comment"]);
    $event['link'] = '!ссылкаНаПрофиль!';
}
$month = ['', _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("August"), _("September"), _("October"), _("November"), _("December")];
$monthNumber = date("n", $event['datetime']);
?>
<!--<li data-event-id="--><? //= $event['event_id'] ?><!--"-->
<!--    class="event system-event --><? //= ($event['view_status']) ? '' : 'new-event' ?><!--  readable-here">-->
<!---->
<!--    <span class="before --><? //= $bg ?><!-- --><? //= ($event['view_status']) ? '' : 'new-event' ?><!--"><i class="--><? //= $icon ?><!--"></i></span>-->
<!--    <div class="position-relative">-->
<!--        <span class="date">--><? //= date("d.m H:i", $event['datetime']); ?><!--</span>-->
<!--        <img src="/--><? //= getAvatarLink($event['author_id']) ?><!--" class="avatar mr-2">-->
<!--        <span class="font-weight-bold">--><? //= $event['name'] ?><!-- --><? //= $event['surname'] ?><!--</span>-->
<!--    </div>-->
<!--    <p class="eventText">--><? //= $action ?><!--</p>-->
<!--</li>-->
<a href="/../<?= $event['link'] ?>" class="text-decoration-none text-dark">
    <li data-event-id="<?= $event['event_id'] ?>"
        class="event <?= $event['action'] ?> <?= ($event['view_status']) ? '' : 'new-event' ?> <?= ($event['action'] == 'createtask') ? '' : 'readable-here' ?> task mb-3">

        <div class="eventDiv position-relative">
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
                <div class="col-5">
                    <p class="mb-0 font-weight-bold text-area-message"><?= $eventText; ?></p>
                    <div>
                        <?php if (!empty($eventDop)) : ?>
                            <span class="text-secondary"><?= $eventDop ?></span>
                        <?php else:?>
                        <?php if ($event['author_id'] == 1): ?>
                            <span class="text-secondary"><?= _('System message') ?></span>
                        <?php else: ?>
                            <span href="/profile/<?= $event['author_id'] ?>/"
                                  class="text-secondary"><?= $event['name'] ?> <?= $event['surname'] ?></span>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-4">
                    <div class="float-right statusText font-weight-bold text-right text-<?= $bg ?>">
                        <?= $action ?>
                    </div>
                </div>
            </div>
    </li>
</a>



