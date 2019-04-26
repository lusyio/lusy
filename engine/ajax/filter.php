<?php
global $pdo;
global $datetime;
global $id;
global $idc;

$queryFromFront = json_decode($_POST['query'], true);
$text = $queryFromFront['query'];
$statusList = $queryFromFront['status'];
$query = "SELECT t.id, t.name, t.datedone, t.datepostpone, t.manager, u1.surname AS surnamem, u1.name AS namem, u2.surname AS surnamew, u2.name AS namew, t.worker, t.status FROM tasks t LEFT JOIN users u1 ON t.manager=u1.id LEFT JOIN users u2 ON t.worker=u2.id WHERE t.idcompany=:companyId AND t.name LIKE :text";

if (sizeof($statusList)>0 && validateStatuses($statusList)) {
    $filters = implode(', ', $queryFromFront['status']);
    $queryBase .= " AND status IN (" . $filters . ")";
}

$dbh = $pdo->prepare($query);
$dbh->execute(array(':companyId' => $idc, ':text' => '%'.$text.'%'));
$resultData = $dbh->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($resultData);

function validateStatuses($statuses)
{
    $allowedStatuses = ['new', 'inwork', 'pending' , 'returned' ,'overdue','canceled' , 'done'];
    foreach ($statuses as $status) {
        if (!in_array($status, $allowedStatuses)) {
            return false;
        }
    }
    return true;
}
