<div class="card">
<?php
global $idc;
$inviteeMail = 'mr-kelevras@yandex.ru';
$template = 'user-welcome';

require_once 'engine/phpmailer/LusyMailer.php';
$mail = new \PHPMailer\PHPMailer\LusyMailer();
$mail->addAddress($inviteeMail);
$mail->isHTML();
$mail->Subject = "Добро пожаловать в Lusy.io";
$companyName = DBOnce('idcompany', 'company', 'id='.$idc);
$args = [
    'companyName' => $companyName,
];
$mail->setMessageContent($template, $args);
//$mail->send();

include 'engine/phpmailer/templates/ru/content-header.php';
include 'engine/phpmailer/templates/ru/'.$template.'.php';
include 'engine/phpmailer/templates/ru/content-footer.php';
?>
</div>
