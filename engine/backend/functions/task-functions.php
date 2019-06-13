<?php

function setStatus($taskId, $status, $postponeDate = null)
{
    global $pdo;
    if (is_null($postponeDate)) {
        $sql = $pdo->prepare('UPDATE `tasks` SET `status` = :status WHERE id = :taskId');
        $data = [
            ':taskId' => $taskId,
            ':status' => $status
        ];
    } else {
        $sql = $pdo->prepare('UPDATE `tasks` SET `status` = :status, datepostpone = :datepostpone WHERE id = :taskId');
        $data = [
            ':taskId' => $taskId,
            ':status' => $status,
            ':datepostpone' => $postponeDate,
        ];
    }
    $sql->execute($data);
}

function resetViewStatus($taskId)
{
    global $id;
    global $pdo;
    $viewStatus = [];
    $viewStatus[$id]['datetime'] = time();
    $viewStatusJson = json_encode($viewStatus);
    $viewQuery = $pdo->prepare('UPDATE `tasks` SET view_status = :viewStatus where id=:taskId');
    $viewQuery->execute(array(':viewStatus' => $viewStatusJson, ':taskId' => $taskId));
}

function addSendOnReviewComments($taskId, $report)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0 ,`datetime` = :datetime");
    $commentData = [
        ':status' => 'report',
        ':commentText' => $report,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
    $commentId = $pdo->lastInsertId();

    $commentData[':status'] = 'system';
    $commentData[':commentText'] = 'report';
    $sql->execute($commentData);

    return $commentId;
}

function addPostponeComments($taskId, $date, $text)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'request:' . $date,
        ':commentText' => $text,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
    $commentId = $pdo->lastInsertId();
    echo $commentId;
    $commentData[':status'] = 'system';
    $commentData[':commentText'] = 'postpone';
    $sql->execute($commentData);

    return $commentId;
}

function addWorkReturnComments($taskId, $date, $text)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'returned',
        ':commentText' => $text,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),

    ];
    $sql->execute($commentData);
    $commentId = $pdo->lastInsertId();

    $commentData[':status'] = 'system';
    $commentData[':commentText'] = 'returned';
    $sql->execute($commentData);

    return $commentId;
}

function addFinalComments($taskId, $status)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => $status,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),

    ];
    $sql->execute($commentData);
}

function addTaskCreateComments($taskId, $workerId, $coworkers)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => 'taskcreate',
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
    $commentData[':commentText'] = 'newworker:' . $workerId;
    $sql->execute($commentData);
    foreach ($coworkers as $coworker) {
        $commentData[':commentText'] = 'addcoworker:' . $coworker;
        $sql->execute($commentData);
    }
}

function addChangeDateComments($taskId, $status)
{
    global $id;
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => $status,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),

    ];
    $sql->execute($commentData);

}

function addChangeExecutorsComments($taskId, $action, $executorId)
{
    global $id;
    global $pdo;
    $comment = $action . ':' . $executorId;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => $comment,
        ':iduser' => $id,
        ':idtask' => $taskId,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
}
function addOverdueComment($taskId)
{
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => 'overdue',
        ':iduser' => '0',
        ':idtask' => $taskId,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
}
