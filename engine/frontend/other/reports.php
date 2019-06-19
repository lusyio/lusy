<!--<form method="post">-->
<!--    <button type="submit" class="btn btn-link mb-3 pl-0" name="send">Отправить на почту</button>-->
<!--</form>-->
<!--<div class="card">-->
<?php
//global $idc;
//$inviteeMail = 'mr-kelevras@yandex.ru';
//$template = 'company-welcome';
//
//require_once 'engine/phpmailer/LusyMailer.php';
//require_once 'engine/phpmailer/Exception.php';
//$mail = new \PHPMailer\PHPMailer\LusyMailer();
//try {
//    $mail->addAddress($inviteeMail);
//
//    $mail->isHTML();
//    $mail->Subject = "Добро пожаловать в Lusy.io";
//    $companyName = DBOnce('idcompany', 'company', 'id=' . $idc);
//    $args = [
//        'companyName' => $companyName,
//    ];
//    $mail->setMessageContent($template, $args);
//} catch (Exception $e) {
//
//}
//
//if (isset($_POST["send"])) {
//    $mail->send();
//    echo 'отправлено';
//}
//
//include 'engine/phpmailer/templates/ru/content-header.php';
//include 'engine/phpmailer/templates/ru/'.$template.'.php';
//include 'engine/phpmailer/templates/ru/content-footer.php';
//?>
<!---->
<!---->
<!--</div>-->

<div class="card">
    <div class="card-body text-center">
        <h2 class="d-inline text-uppercase font-weight-bold">
            Demo
        </h2>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="text-center">
            Общая статистика
        </h5>
        <hr>
        <div class="row text-center">
            <div class="col">
                <p class="text-muted">Выполнено</p>
                <p>50</p>
            </div>
            <div class="col">
                <p class="text-muted">Просрочено</p>
                <p>12</p>
            </div>
            <div class="col">
                <p class="text-muted">Комментарии</p>
                <p>123</p>
            </div>
            <div class="col">
                <p class="text-muted">События</p>
                <p>300</p>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3 w-50" >
    <div class="card-body pb-0 border-bottom">
        <div class="row">
            <div class="col-2">
            </div>
            <div class="col">
                <p>Имя фамилия</p>
                <p>Выполнено</p>
                <p>Просрочено</p>
                <p>Комментарии</p>
                <p>События</p>
            </div>
        </div>
    </div>
</div>
