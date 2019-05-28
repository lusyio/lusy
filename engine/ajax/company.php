<?php
global $pdo;
global $id;
global $idc;

require_once 'engine/backend/functions/company-functions.php';

if ($_POST['module'] == 'fireUser') {
    $userToFireId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    fireUser($userToFireId);
}
if ($_POST['module'] == 'restoreUser') {
    $userToRestoreId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    restoreUser($userToRestoreId);
}
