<?php

global $id;
global $idc;
global $pdo;


// закомментил для отладки
//if (DBOnce('role', 'users', 'id='.$id) == 'worker') {
//    header('location:/');
//    ob_flush();
//    die;
//}
if (isset($_POST['invitee-name']) && $_POST['position']) {
    $inviteeName = filter_var($_POST['invitee-name'], FILTER_SANITIZE_STRING);
    $inviteePosition = filter_var($_POST['position'], FILTER_SANITIZE_STRING);
    if (isset($_POST['invitee-mail'])) {
        $inviteeMail = filter_var($_POST['invitee-mail'], FILTER_SANITIZE_STRING);
    } else {
        $inviteeMail = '';
    }
    createInvite($inviteeName, $inviteeMail, $inviteePosition);
}



$invitesQuery = $pdo->prepare('SELECT invite_id, invitee_name, code, invite_date, status, email, invitee_position FROM invitations WHERE company_id=:companyId');
$invitesQuery->execute(array(':companyId' => $idc));
$invites = $invitesQuery->fetchAll(PDO::FETCH_ASSOC);