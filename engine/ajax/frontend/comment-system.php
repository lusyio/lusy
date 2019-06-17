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
    $workerLink = '<a href="/profile/' . $newWorkerId .'/">' . $workername . '</a>';
    $text = $_newWorker . ' - ' . $workerLink;
}
if ($comment[0] == 'addcoworker') {
    $newCoworkerId = $comment[1];
    $workername = DBOnce('name', 'users', 'id=' . $newCoworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $newCoworkerId);
    $coworkerLink = '<a href="/profile/' . $newCoworkerId .'/">' . $workername . '</a>';
    $text = $_newCoworker . ' - ' . $coworkerLink;
}
if ($comment[0] == 'removecoworker') {
    $coworkerId = $comment[1];
    $workername = DBOnce('name', 'users', 'id=' . $coworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $coworkerId);
    $coworkerLink = '<a href="/profile/' . $coworkerId .'/">' . $workername . '</a>';
    $text = $_coworkerWasRemoved . ' - ' . $coworkerLink;
}
?>

<div class="mt-5 mb-5" id="<?= $c['id'] ?>">
    <div class="text-center system text-secondary position-relative">
	    <div class="system-text">
	        <span><?= $text ?></span>
	    </div>
    </div>
</div>
