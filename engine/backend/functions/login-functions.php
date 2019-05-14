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
    $cookieString = encryptCookie($initialString);
    return $cookieString;
}

function parseCookie($cookieString)
{
    $decryptedCookie = decryptCookie($cookieString);
    if (!$decryptedCookie) {
        return false;
    }
    $result = mb_split('-', $decryptedCookie);
    $resultArray = [
        'sid' => $result[0],
        'uid' => $result[1],
        'timestamp' => $result[2],
    ];
    return $resultArray;
}
define('ENCRYPTION_KEY', '0DBC8F2F74F2F00E0D1B0C1D4552A4B4'); //TODO change encryption key

function encryptCookie($cookie) {
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($cookie, $cipher, ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary=true);
    $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );
    return $ciphertext;
}

function decryptCookie($cookie) {
    $c = base64_decode($cookie);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
    $plaintext = openssl_decrypt($ciphertext_raw, $cipher, ENCRYPTION_KEY, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPTION_KEY, $as_binary=true);
    if (hash_equals($hmac, $calcmac))
    {
        return $plaintext;
    }
    return false;
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
