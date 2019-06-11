<?php
ob_start();
require_once 'engine/backend/functions/login-functions.php';
$sessionCookie = parseCookie($_COOKIE['token']);
removeSessions($sessionCookie['sid']);
setcookie('token', null, -1, '/');
unset($_COOKIE[session_name()]);
unset($_SESSION);
//session_destroy();
header('location: /login/');
ob_end_flush();
?>