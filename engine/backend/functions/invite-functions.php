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

function createInvite($inviteeMail, $inviteePosition)
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
    $inviteQuery = $pdo->prepare('INSERT INTO invitations(company_id, code, invite_date, status, email, invitee_position) VALUES (:companyId, :code, :inviteDate, :inviteStatus, :email, :inviteePosition)');
    $queryData = [
        ':companyId' => $idc,
        ':code' => $code,
        ':inviteDate' => time(),
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
    $readInviteQuery = $pdo->prepare('SELECT invite_id, company_id, code, invite_date, status, email, invitee_position FROM invitations WHERE invite_id=:inviteId');
    $readInviteQuery->execute(array(':inviteId' => $inviteId));
    $result = $readInviteQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}
function readInviteByCode($code)
{
    global $pdo;
    $readInviteQuery = $pdo->prepare('SELECT i.invite_id, i.company_id, i.code, i.invite_date, i.status, i.email, i.invitee_position, c.idcompany AS company_name FROM invitations i LEFT JOIN company c ON i.company_id=c.id WHERE i.code=:code');
    $readInviteQuery->execute(array(':code' => $code));
    $result = $readInviteQuery->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function updateInvite($inviteId, $newUserId)
{
    global $pdo;
    $updateInviteQuery = $pdo->prepare('UPDATE invitations SET status=:newUserId WHERE invite_id=:inviteId');
    $updateInviteQuery->execute(array('newUserId' => $newUserId, 'inviteId' => $inviteId));
}

function isEmailInvited($inviteeMail)
{
    global $pdo;
    $emailQuery = $pdo->prepare('SELECT invite_id FROM invitations WHERE email=:email');
    $emailQuery->execute(array(':email' => mb_strtolower($inviteeMail)));
    return (boolean) $emailQuery->fetch(PDO::FETCH_ASSOC);
}
