<?php

require_once 'engine/backend/functions/common-functions.php';

if ($_POST['module'] == 'count') {
    $newTaskCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action NOT LIKE "comment"');
    $overdueCount = DBOnce('count(*)','tasks','(worker='.$id.' or manager='.$id.') and status="overdue"');
    $newCommentCount = DBOnce('count(*)', 'events', 'recipient_id='.$id.' AND view_status=0 AND action LIKE "comment"');
    $newMailCount = DBOnce('count(*)', 'mail', 'recipient='.$id.' AND view_status=0');

    $result = [
        'task' => $newTaskCount,
        'hot' => $overdueCount,
        'comment' => $newCommentCount,
        'mail' => $newMailCount,
    ];
    echo json_encode($result);
}