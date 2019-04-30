<?php
require_once 'engine/backend/functions/login-functions.php';
$_SESSION['auth'] = null;
$sessionCookie = parseCookie($_COOKIE['token']);
removeSessions($sessionCookie['sid']);
setcookie('token', null, -1, '/');
session_destroy();
header('location: /login/');
ob_end_flush();
?>