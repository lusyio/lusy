<?php
global $pdo;
if (isset($_GET['restore']) && isset($_GET['code']) && isset($_POST['password'])) {
    $restoredId = filter_var($_GET['restore'], FILTER_SANITIZE_NUMBER_INT);
    $restoredCode = filter_var($_GET['code'], FILTER_SANITIZE_STRING);
    if (DBOnce('count(*)', 'password_restore', 'user_id=' . $restoredId . ' AND code=\'' . $restoredCode. '\'')) {
        $newPassword = trim($_POST['password']);
        $newPassword = filter_var($newPassword, FILTER_SANITIZE_STRING);
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updatePasswordQuery = $pdo->prepare('UPDATE users SET password = :pass WHERE id = :userId');
        $result = $updatePasswordQuery->execute(array(':pass' => $passwordHash, ':userId' => $restoredId));
        $removeRestoreCodeQuery = $pdo->prepare('DELETE FROM password_restore WHERE user_id=:userId AND code=:code');
        $removeRestoreCodeQuery->execute(array(':userId' => $restoredId, ':code' => $restoredCode));
        if ($result) {
            $_SESSION['login'] = DBOnce('email', 'users', 'id=' . $restoredId);
            $_SESSION['password'] = $newPassword;
            $idc = DBOnce('idcompany', 'users', 'id=' . $restoredId);
            $_SESSION['idcompany'] = DBOnce('idcompany', 'company', 'id=' . $idc);
            header('location: /login/');
        }
    }
}