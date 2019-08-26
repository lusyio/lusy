<?php
require_once __ROOT__ . '/engine/backend/functions/common-functions.php';

$userId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$code = filter_var($_GET['code'], FILTER_SANITIZE_EMAIL);
$unsubscribeStatus = unsubscribeEmail($userId, $code);