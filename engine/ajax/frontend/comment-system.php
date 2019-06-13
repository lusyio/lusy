<?php
if ($c['comment'] == 'report') {
    $text = '<a href="/profile/' . $c['iduser'] .'/">' . $nameuser . ' ' . $surnameuser . '</a> ' . $_logSendOnReview . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'returned') {
    $text = '<a href="/profile/' . $c['iduser'] .'/">' . $nameuser . ' ' . $surnameuser . '</a> ' . $_logWorkReturn . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'done') {
    $text = $_logWorkDone . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'canceled') {
    $text = $_logCancelTask . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'taskcreate') {
    $text = $_taskWasCreated . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'postpone') {
    $text = $_postponeWasRequested . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'confirmdate') {
    $text = $_postponeWasConfirmed . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'canceldate') {
    $text = $_postponeWasCanceled . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'senddate') {
    $text = $_dateWasChanged . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if ($c['comment'] == 'overdue') {
    $text = $_taskOverdue . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if (preg_match('~^newworker:~', $c['comment'])) {
    $newWorkerId = preg_split('~:~', $c['comment'])[1];
    $workername = DBOnce('name', 'users', 'id=' . $newWorkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $newWorkerId);
    $workerLink = '<a href="/profile/' . $newWorkerId .'/">' . $workername . '</a>';
    $text = $_newWorker . ' - ' . $workerLink . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if (preg_match('~^addcoworker:~', $c['comment'])) {
    $newCoworkerId = preg_split('~:~', $c['comment'])[1];
    $workername = DBOnce('name', 'users', 'id=' . $newCoworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $newCoworkerId);
    $coworkerLink = '<a href="/profile/' . $newCoworkerId .'/">' . $workername . '</a>';
    $text = $_newCoworker . ' - ' . $coworkerLink . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
if (preg_match('~^removecoworker:~', $c['comment'])) {
    $coworkerId = preg_split('~:~', $c['comment'])[1];
    $workername = DBOnce('name', 'users', 'id=' . $coworkerId) . ' ' . DBOnce('surname', 'users', 'id=' . $coworkerId);
    $coworkerLink = '<a href="/profile/' . $coworkerId .'/">' . $workername . '</a>';
    $text = $_coworkerWasRemoved . ' - ' . $coworkerLink . '. ';
    $text .= date('d.m H:i', $c['datetime']);
}
?>

<div class="mt-5 mb-5 <?= ($isNew)? 'bg-success':'' ?>" id="<?= $c['id'] ?> ">
    <div class="text-center system text-secondary position-relative">
	    <div class="system-text">
	        <span><?= $text ?></span>
	    </div>
    </div>
</div>
