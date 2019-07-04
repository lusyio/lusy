<?php

require_once __ROOT__ . '/engine/backend/functions/reg-functions.php';
require_once __ROOT__ . '/engine/backend/functions/invite-functions.php';

$userLanguage = 'ru';
require_once __ROOT__ . '/engine/backend/lang/'.$userLanguage.'.php';

$code = filter_var($_GET['join'], FILTER_SANITIZE_STRING);

$inviteData = readInviteByCode($code);
if (!$inviteData || $inviteData['status']) {
    die("Invite doesnt't exist or expired");
}
$email = $inviteData['email'];
$companyName = $inviteData['company_name'];

