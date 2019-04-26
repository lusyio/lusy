<?php
global $pdo;
global $datetime;
global $id;
global $idc;

$queryFromFront = json_decode($_POST['data'], true);
$text = $queryFromFront['query'];
$statusList = $queryFromFront['status'];
$roleList = $queryFromFront['role'];
$query = "SELECT t.id, t.name, t.datedone, t.datepostpone, t.manager, u1.surname AS surnamem, u1.name AS namem, u2.surname AS surnamew, u2.name AS namew, t.worker, t.status FROM tasks t LEFT JOIN users u1 ON t.manager=u1.id LEFT JOIN users u2 ON t.worker=u2.id WHERE t.idcompany=:companyId AND t.name LIKE :text";

if (sizeof($statusList) > 0 && validateStatuses($statusList)) {
    $filters = implode("', '", $statusList);
    var_dump($filters);
    $query .= " AND t.status IN ('" . $filters . "')";
}

if (sizeof($roleList) > 0 && validateRoles($roleList)) {
    foreach ($roleList as $role) {
        if ($role == 'worker') {
            $query .= " AND t.worker=:userId";
            $dbhValues[':userId'] = $id;
        }
        if ($role == 'manager') {
            $query .= " AND t.manager=:userId";
            $dbhValues[':userId'] = $id;
        }
    }
}
$dbh = $pdo->prepare($query);
$dbhValues = [
    ':companyId' => $idc,
    ':text' => '%' . $text . '%',
];
$dbh->execute($dbhValues);
$resultData = $dbh->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultData);

function validateStatuses($statuses)
{
    $allowedStatuses = ['new', 'inwork', 'pending', 'returned', 'overdue', 'canceled', 'done'];
    foreach ($statuses as $status) {
        if (!in_array($status, $allowedStatuses)) {
            return false;
        }
    }
    return true;
}

function validateRoles($roles)
{
    $allowedRoles = ['worker', 'manager'];
    foreach ($roles as $role) {
        if (!in_array($role, $allowedRoles)) {
            return false;
        }
    }
    return true;
}
