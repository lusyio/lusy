<?php
global $pdo;
global $id;
global $idc;
global $roleu;

require_once 'engine/backend/functions/company-functions.php';

if ($_POST['module'] == 'fireUser' && $roleu == 'ceo') {
    $userToFireId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    fireUser($userToFireId);
}
if ($_POST['module'] == 'restoreUser' && $roleu == 'ceo') {
    $userToRestoreId = filter_var($_POST['userId'], FILTER_SANITIZE_NUMBER_INT);
    restoreUser($userToRestoreId);
}
