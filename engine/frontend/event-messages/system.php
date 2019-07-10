<?php
require_once __ROOT__ . '/engine/backend/functions/mail-functions.php';
?>
<li data-event-id="<?= $event['event_id'] ?>"
    class="d-none event system-event <?= ($event['view_status']) ? '' : 'new-event' ?>  readable-here">
    <?php
    if ($event['action'] == 'newuser') { // новый пользователь
        $bg = 'bg-success';
        $icon = 'fas fa-user';
        $action = $GLOBALS['_newUserRegistered'] . ' <a href="/profile/' . $event["comment"] . '/">' . fiomess($event["comment"]) . '</a>';
    }
    if ($event['action'] == 'newcompany') { // регистрация компании
        $bg = 'bg-success';
        $icon = 'fas fa-exclamation';
        $action = $GLOBALS['_newCompanyRegistered'];
    }
    if ($event['action'] == 'newachievement') { // новое достижение
        $bg = 'bg-success';
        $icon = 'fas fa-trophy';
        $action = $GLOBALS['_youGotNewAchievement'] . ' - <a href="/awards/">';
        $action .= $GLOBALS['_' . $event['comment']] . '</a>!';
    }
    ?>
    <span class="before <?= $bg ?> <?= ($event['view_status']) ? '' : 'new-event' ?>"><i class="<?= $icon ?>"></i></span>
    <div class="position-relative">
        <span class="date"><?= date("d.m H:i", $event['datetime']); ?></span>
        <img src="/<?= getAvatarLink($event['author_id']) ?>" class="avatar mr-2">
        <span class="font-weight-bold"><?= $event['name'] ?> <?= $event['surname'] ?></span>
    </div>
    <p class="eventText"><?= $action ?></p>
</li>