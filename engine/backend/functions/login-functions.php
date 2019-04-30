<?php
function removeExcessiveSessionsIfExists($userId)
{
    global $pdo;
    $sessionsQuery = 'SELECT session_id, user_id, timestamp FROM user_sessions WHERE user_id=:userId ORDER BY timestamp';
    $dbh = $pdo->prepare($sessionsQuery);
    $dbh->execute(array(':userId' => $userId));
    $sessions = $dbh->fetchAll(PDO::FETCH_ASSOC);
    if (count($sessions) > 9) {
        $sessionsToRemove = [];
        while (count($sessions) > 9) {
            $sessionsToRemove[] = array_shift($sessions)['session_id'];
        }
        removeSessions($sessionsToRemove);
    }
}

function createSession($userId, $timestamp)
{
    global $pdo;
    $newSessionQuery = 'INSERT INTO user_sessions(user_id, timestamp) VALUES (:userId, :timestamp)';
    $dbh = $pdo->prepare($newSessionQuery);
    $dbh->execute(array(':userId' => $userId, ':timestamp' => $timestamp));
    $sessionId = $pdo->lastInsertId();
    return $sessionId;
}

function createCookieString($sessionId, $userId, $timestamp)
{
    $initialString = $sessionId . '-' . $userId . '-' . $timestamp;
    $cookieString = $initialString; //TODO add encrypt / добавить шифрование
    return $cookieString;
}

function parseCookie($cookieString)
{
    $decryptedCookie = $cookieString; //TODO add decrypt / добавить дешифрование
    $result = mb_split('-', $decryptedCookie);
    $resultArray = [
        'sid' => $result[0],
        'uid' => $result[1],
        'timestamp' => $result[2],
    ];
    return $resultArray;
}

function removeSessions($sessionIds) {
    global $pdo;
    if (is_array($sessionIds)) {
        $ids = implode(',', $sessionIds);
    } else {
        $ids = $sessionIds;
    }
    $query = 'DELETE FROM user_sessions WHERE session_id IN (' . $ids  . ')';
    $dbh = $pdo->prepare($query);
    $dbh->execute();
}

function isCookieExistAndValidByTimestamp($sessionCookie) {
    $timestampInDb = DBOnce('timestamp', 'user_sessions', 'session_id='.$sessionCookie['sid']);
    return $timestampInDb == $sessionCookie['timestamp'];
}

function updateCookieTime($sessionCookie, $timestamp)
{
    global $pdo;
    $query = 'UPDATE user_sessions SET timestamp=:timestamp WHERE session_id=:sessionId';
    $dbh = $pdo->prepare($query);
    $dbh->execute(array(':sessionId' => $sessionCookie['sid'], ':timestamp' => $timestamp));
    $sessionId = $pdo->lastInsertId();
    return $sessionId;
}
