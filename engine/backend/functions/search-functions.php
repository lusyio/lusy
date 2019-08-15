<?php
function mainSearch($request)
{
    $taskResult = searchInTaskNames($request);
    $fileResult = searchInFileNames($request);
    $commentResult = searchInComments($request);

    $result = [
        'task' => $taskResult,
        'file' => $fileResult,
        'comment' => $commentResult,
    ];
    return $result;
}

function searchInTaskNames($request)
{
    global $id;
    global $pdo;
    //поиск по задачам
    $taskSearchQuery = $pdo->prepare('SELECT t.id, t.name FROM tasks t 
      LEFT JOIN task_coworkers c on t.id = c.task_id 
      WHERE (t.manager = :userId OR t.author = :userId OR c.worker_id = :userId) AND t.name LIKE :request');
    $taskSearchQuery->execute(array(':userId' => $id, ':request' => '%' . $request . '%'));
    $result = $taskSearchQuery->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function searchInFileNames($request)
{
    global $id;
    global $pdo;

    $fileSearchQuery = $pdo->prepare(
        "SELECT u.file_id, u.file_name, u.file_size, u.file_path, u.comment_type, u.comment_id, 
       IF(c.idtask IS NULL AND u.comment_type='task', u.comment_id, c.idtask) AS idtask, 
       u.author, us.surname, us.name, t.name AS taskName, 
       IF(c.datetime IS NULL, IF(t.datecreate IS NULL, m.datetime, t.datecreate), c.datetime) AS uploadDate
FROM `uploads` u
       LEFT JOIN comments c on u.comment_id=c.id AND u.comment_type='comment'
       LEFT JOIN users us ON u.author = us.id
       LEFT JOIN tasks t ON (t.id = u.comment_id AND u.comment_type='task') OR (t.id = c.idtask AND u.comment_type='comment')
       LEFT JOIN task_coworkers tc ON tc.task_id = t.id
       LEFT JOIN mail m ON m.message_id = u.comment_id AND u.comment_type='conversation'
WHERE (u.author = :userId  OR tc.worker_id = :userId) AND u.file_name LIKE :request AND u.is_deleted = 0");
    $fileSearchQuery->execute(array(':userId' => $id, ':request' => '%' . $request . '%'));
    $result = $fileSearchQuery->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

function searchInComments($request)
{
    global $id;
    global $pdo;

    $commentSearchQuery = $pdo->prepare("SELECT c.id, c.idtask, c.comment FROM comments c 
  LEFT JOIN tasks t ON c.idtask = t.id 
  LEFT JOIN task_coworkers tc on t.id=tc.task_id WHERE tc.worker_id = :userId AND c.comment LIKE :request");
    $commentSearchQuery->execute(array(':userId' => $id, ':request' => '%' . $request . '%'));
    $result = $commentSearchQuery->fetchAll(PDO::FETCH_ASSOC);
    return $result;

}
