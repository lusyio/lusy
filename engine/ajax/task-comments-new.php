<?php
global $idc;
global $tariff;
$tryPremiumLimits = getFreePremiumLimits($idc);
$usePremiumCloud = false;
$idtask = filter_var($_POST['it'], FILTER_SANITIZE_NUMBER_INT);
$text = filter_var(trim($_POST['text']), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
$unsafeGoogleFiles = json_decode($_POST['googleAttach'], true);
$googleFiles = [];
foreach ($unsafeGoogleFiles as $k => $v) {
    $googleFiles[] = [
        'name' => filter_var($k, FILTER_SANITIZE_STRING),
        'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
        'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
    ];
}
$unsafeDropboxFiles = json_decode($_POST['dropboxAttach'], true);
$dropboxFiles = [];
foreach ($unsafeDropboxFiles as $k => $v) {
    $dropboxFiles[] = [
        'name' => filter_var($k, FILTER_SANITIZE_STRING),
        'path' => filter_var($v['link'], FILTER_SANITIZE_STRING),
        'size' => filter_var($v['size'], FILTER_SANITIZE_NUMBER_INT),
    ];
}
$datetime = time();
// создаем комментаприй
$sql = $pdo->prepare("INSERT INTO `comments` (`comment`, `status`, `iduser`, `idtask`, `view`, `datetime`) VALUES (:comment, :status, :iduser, :idtask, :view, :datetime)");
$sql->execute(array(':comment' => $text, ':status' => 'comment', ':iduser' => $id, ':idtask' => $idtask, 'view' => 0, ':datetime' => time()));
$idcomment = $pdo->lastInsertId();

if (count($_FILES) > 0) {
    uploadAttachedFiles('comment', $idcomment);
}
if (count($googleFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
    addGoogleFiles('comment', $idcomment, $googleFiles);
    $usePremiumCloud = true;
}
if (count($dropboxFiles) > 0 && ($tariff == 1 || $tryPremiumLimits['cloud'] < 3)) {
    addDropboxFiles('comment', $idcomment, $dropboxFiles);
    $usePremiumCloud = true;
}

if ($tariff == 0) {
    if ($usePremiumCloud) {
        updateFreePremiumLimits($idc, 'cloud');
    }
}

addCommentEvent($idtask, $idcomment);

?>