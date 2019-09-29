<?php
$comment = preg_split('~:~', $c['comment']);

if ($comment[0] == 'report') {
    $text = '<a href="/profile/' . $c['iduser'] .'/">' . $nameuser . ' ' . $surnameuser . '</a> ' . $_logSendOnReview;
}
if ($comment[0] == 'returned') {
    $text = '<a href="/profile/' . $c['iduser'] .'/">' . $nameuser . ' ' . $surnameuser . '</a> ' . $_logWorkReturn . '. ';
    if (!empty($comment[1])) {
        $text .= $_taskNewDeadline . ': ' . date('d.m.Y',$comment[1]);
    }
}
if ($comment[0] == 'done') {
    $text = $_logWorkDone;
}
if ($comment[0] == 'canceled') {
    $text = $_logCancelTask;
}
if ($comment[0] == 'taskcreate') {
    $text = $_taskWasCreated;
}
if ($comment[0] == 'postpone') {
    $text = $_postponeWasRequested . '. ';
    if (!empty($comment[1])) {
        $text .= $_taskNewDeadline . ': ' . date('d.m.Y',$comment[1]);
    }
}
if ($comment[0] == 'confirmdate') {
    $text = $_postponeWasConfirmed . '. ';
    if (!empty($comment[1])) {
        $text .= $_taskNewDeadline . ': ' . date('d.m.Y',$comment[1]);
    }
}
if ($comment[0] == 'canceldate') {
    $text = $_postponeWasCanceled;
}
if ($comment[0] == 'senddate') {
    $text = $_dateWasChanged . '. ';
    if (!empty($comment[1])) {
        $text .= $_taskNewDeadline . ': ' . date('d.m.Y',$comment[1]);
    }
}
if ($comment[0] == 'overdue') {
    $text = $_taskOverdue;
}
if ($comment[0] == 'newworker') {
    $newWorkerId = $comment[1];
    $workername = DBOnce('name', 'users', 'id=' . $newWorkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $newWorkerId);
    if (trim($workername) == '') {
        $workername = DBOnce('email', 'users', 'id=' . $newWorkerId);
    }
    $workerLink = '<a href="/profile/' . $newWorkerId .'/">' . $workername . '</a>';
    $text = $_newWorker . ' - ' . $workerLink;
}
if ($comment[0] == 'addcoworker') {
    $newCoworkerId = $comment[1];
    $workername = DBOnce('name', 'users', 'id=' . $newCoworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $newCoworkerId);

    if (trim($workername) == '') {
        $workername = DBOnce('email', 'users', 'id=' . $newCoworkerId);
    }
    $coworkerLink = '<a href="/profile/' . $newCoworkerId .'/">' . $workername . '</a>';
    $text = $_newCoworker . ' - ' . $coworkerLink;
}
if ($comment[0] == 'removecoworker') {
    $coworkerId = $comment[1];
    $workername = DBOnce('name', 'users', 'id=' . $coworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $coworkerId);

    if (trim($workername) == '') {
        $workername = DBOnce('email', 'users', 'id=' . $coworkerId);
    }

    $coworkerLink = '<a href="/profile/' . $coworkerId .'/">' . $workername . '</a>';
    $text = $_coworkerWasRemoved . ' - ' . $coworkerLink;
}
if ($comment[0] == 'addsubtask') {
    $subTaskAvailabilityQuery = $pdo->prepare("SELECT id FROM tasks t  left join task_coworkers tc ON t.id = tc.task_id WHERE t.id = :taskId AND (t.manager = :userId OR t.worker = :userId OR tc.worker_id = :userId)");
    $subTaskAvailabilityQuery->execute([':taskId' => $comment[1], ':userId' => $id]);
    $subTaskAvailability = $subTaskAvailabilityQuery->fetch(PDO::FETCH_COLUMN);
    if (!$subTaskAvailability && $roleu != 'ceo') {
        return;
    }
    $text = '<a href="/task/' . $comment[1] .'/">Создана подзадача</a>';
}
if ($comment[0] == 'edittask') {
    $text = _('The task description was edited');
}
$date = date('d.m H:i' ,$c['datetime']);
?>
<div class="mt-3 mb-3 system text-center text-secondary position-relative" id="<?= $c['id'] ?>">
    <div class="system-text pt-3 pb-3">
        <span><?= $text ?></span>
    </div>
    <div class="small pt-3 pb-3 pl-md-3 position-absolute text-nowrap system-date"><?= $date ?></div>
</div>
