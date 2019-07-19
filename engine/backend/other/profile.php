<?php
global $id;
global $idc;
if (empty($_GET['profile'])) {
    header("location:/profile/{$id}/");
    ob_end_flush();
    die;
}
$profileId = $_GET["profile"];
$userData = getUserData($profileId);

$achievementProfile = getUserNonMultipleAchievements($profileId);

// Проверка на попытку просмотра профиля чужой компании
if (!isset($userData['idcompany']) || $idc != $userData['idcompany']) {
    header("location:/");
    ob_end_flush();
    die;
}

if (empty($userData['phone'])) {
    $userData['phone'] = '--';
} else {
    $userData['phone'] = strval($userData['phone']);
    $userData['phone'] = '+' . substr($userData['phone'], 0, 1) . ' (' . substr($userData['phone'], 1, 3) . ') ' . substr($userData['phone'], 4, 3) . '-' . substr($userData['phone'], 7, 2) . '-' . substr($userData['phone'], 9, 2);
}

if (empty($userData['email'])) {
    $userData['email'] = '--';
}

$socialNetworks = json_decode($userData['social_networks'], true);

