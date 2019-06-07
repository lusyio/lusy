<form method="post">
    <button type="submit" class="btn btn-link mb-3 pl-0" name="send">Отправить на почту</button>
</form>
<div class="card">
<?php
global $idc;
$inviteeMail = 'mr-kelevras@yandex.ru';
$template = 'user-invite';

require_once 'engine/phpmailer/LusyMailer.php';
require_once 'engine/phpmailer/Exception.php';
$mail = new \PHPMailer\PHPMailer\LusyMailer();
try {
    $mail->addAddress($inviteeMail);

    $mail->isHTML();
    $mail->Subject = "Добро пожаловать в Lusy.io";
    $companyName = DBOnce('idcompany', 'company', 'id=' . $idc);
    $args = [
        'companyName' => $companyName,
    ];
    $mail->setMessageContent($template, $args);
} catch (Exception $e) {

}

if (isset($_POST["send"])) {
    $mail->send();
    echo 'отправлено';
}

include 'engine/phpmailer/templates/ru/content-header.php';
include 'engine/phpmailer/templates/ru/'.$template.'.php';
include 'engine/phpmailer/templates/ru/content-footer.php';
?>


</div>
