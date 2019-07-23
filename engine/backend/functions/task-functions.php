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
        $sql = $pdo->prepare('UPDATE `tasks` SET `status` = :status, datedone = :datepostpone WHERE id = :taskId');
        $data = [
            ':taskId' => $taskId,
            ':status' => $status,
            ':datepostpone' => $postponeDate,
        ];
    }
    $sql->execute($data);
}

function setFinalStatus($taskId, $status)
{
    global $pdo;
    $sql = $pdo->prepare('UPDATE `tasks` SET `status` = :status, report = :report WHERE id = :taskId');
    $data = [
        ':taskId' => $taskId,
        ':status' => $status,
        ':report' => time(),
    ];
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
    $commentData[':commentText'] = 'postpone:' . $date;
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
    $commentData[':commentText'] = 'returned:' . $date;
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

function addChangeDateComments($taskId, $status, $date = null)
{
    global $id;
    global $pdo;

    if (is_null($date)) {
        $commentStatus = $status;
    } else {
        $commentStatus = $status . ':' . $date;
    }

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => $commentStatus,
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

function checkSystemTask($taskId)
{
    global $id;
    global $idc;
    global $pdo;

        setFinalStatus($taskId, 'done');
        addFinalComments($taskId, 'done');
        resetViewStatus($taskId);
        //addEvent
        $eventDataForWorker = [
            ':action' => 'workdone',
            ':taskId' => $taskId,
            ':authorId' => 1,
            ':recipientId' => $id,
            ':companyId' => $idc,
            ':datetime' => time(),
            ':viewStatus' => 0,
        ];
        $addEventQuery = $pdo->prepare('INSERT INTO events(action, task_id, author_id, recipient_id, company_id, datetime, view_status) 
      VALUES(:action, :taskId, :authorId, :recipientId, :companyId, :datetime, :viewStatus)');
        $addEventQuery->execute($eventDataForWorker);
}

function addSubTaskComment($parentTask, $subTask)
{
    global $pdo;

    $sql = $pdo->prepare("INSERT INTO `comments` SET `comment` = :commentText, `iduser` = :iduser, `idtask` = :idtask, `status` = :status, `view`=0, `datetime` = :datetime");

    $commentData = [
        ':status' => 'system',
        ':commentText' => 'addsubtask:' . $subTask,
        ':iduser' => '0',
        ':idtask' => $parentTask,
        ':datetime' => time(),
    ];
    $sql->execute($commentData);
}

function checkSubTasksForFinish($taskId)
{
    global $pdo;
    $subTasksQuery = $pdo->prepare("SELECT id, name, description, status, manager, worker, idcompany, report, view, view_status, author, datecreate, datepostpone, datedone, parent_task FROM tasks WHERE parent_task = :taskId");
    $subTasksQuery->execute([':taskId' => $taskId]);
    $subTasks = $subTasksQuery->fetchAll(PDO::FETCH_ASSOC);
    $result = [
        'status' => true,
        'tasks' => [],
    ];
    foreach ($subTasks as $subTask) {
        if ($subTask['status'] != 'canceled' || $subTask['status'] != 'done') {
            $result['status'] = false;
            $result['tasks'][] = [
                'id' => $subTask['id'],
                'name' => $subTask['name'],
            ];
        }
    }
    return $result;
}
