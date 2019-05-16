<?php

function isAuthorized($id)
{
    $position = DBOnce('role', 'users', 'id=' . $id);
    //if ($position == 'seo' || $position == 'admin') {
    if (true) {
        return true;
    }
    return false;
}

function deleteInvite($inviteId)
{
    global $id;
    global $idc;
    global $pdo;
    $authorized = isAuthorized($id);
    if (!$authorized) {
        return false;
    }
    $deleteInviteQuery = $pdo->prepare('DELETE FROM invitations WHERE invite_id=:inviteId AND company_id=:companyId');
    $deleteInviteQuery->execute(array('inviteId' => $inviteId, ':companyId' => $idc));

}

function createInvite($inviteeName, $inviteeMail, $inviteePosition)
{
    global $id;
    global $pdo;
    global $idc;
    $authorized = isAuthorized($id);
    if (!$authorized) {
        return false;
    }
    $possiblePositions = ['admin', 'worker'];
    if (!in_array($inviteePosition, $possiblePositions)) {
        return false;
    }
    $code = str_shuffle(md5(time()));
    while (DBOnce('count(*)', 'invitations', 'code=\'' . $code . '\'')) {
        $code = str_shuffle(md5(time()));
    }
    $inviteQuery = $pdo->prepare('INSERT INTO invitations(company_id, invitee_name, code, invite_date, status, email, invitee_position) VALUES (:companyId, :inviteeName, :code, :inviteDate, :inviteStatus, :email, :inviteePosition)');
    $queryData = [
        ':companyId' => $idc,
        ':inviteeName' => $inviteeName,
        ':code' => $code,
        ':inviteDate' => date("Y-m-d H:i:s"),
        ':inviteStatus' => 0,
        ':email' => $inviteeMail,
        ':inviteePosition' => $inviteePosition,
    ];
    $inviteQuery->execute($queryData);
    return $pdo->lastInsertId();
}

function readInvite($inviteId)
{
    global $id;
    global $pdo;
    global $idc;
    $authorized = isAuthorized($id);
    if (!$authorized) {
        return false;
    }
    $readInviteQuery = $pdo->prepare('SELECT invite_id, company_id, invitee_name, code, invite_date, status, email, invitee_position FROM invitations WHERE invite_id=:inviteId');
    $readInviteQuery->execute(array(':inviteId' => $inviteId));
    $result = $readInviteQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}