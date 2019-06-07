<?php
$requestEmail = mb_strtolower(filter_var($_POST['email'], FILTER_SANITIZE_STRING));
$checkUserExistQuery = $pdo->prepare('SELECT id FROM users WHERE email=:email LIMIT 1');
$checkUserExistQuery->execute(array(':email' => $requestEmail));
$userId = $checkUserExistQuery->fetch(PDO::FETCH_COLUMN);
if ($userId) {
    $restoreCode = str_shuffle(md5(time()));
    $checkCodeExistQuery = $pdo->prepare('SELECT pr_id FROM password_restore WHERE user_id=:userId');
    $checkCodeExistQuery->execute(array(':userId' => $userId));
    $pr = $checkCodeExistQuery->fetchAll(PDO::FETCH_COLUMN);
    if (count($pr)) {
        $removeCode = $pdo->prepare("DELETE FROM password_restore WHERE user_id=:userId");
        $removeCode->execute(array(':userId' => $userId));
    }
    $addCode = $pdo->prepare('INSERT INTO password_restore(user_id, code) VALUES (:userId,:restoreCode)');
    $addCode->execute(array(':userId' => $userId, ':restoreCode' => $restoreCode));
    require_once 'engine/phpmailer/LusyMailer.php';
    require_once 'engine/phpmailer/Exception.php';
    try {
        $mail = new \PHPMailer\PHPMailer\LusyMailer();
        $mail->addAddress($requestEmail);
        $mail->isHTML();
        $mail->Subject = "Восстановление пароля в Lusy.io";
        $args = [
            'restoreLink' => $_SERVER['HTTP_HOST'] . '/restore/' . $userId . '/' . $restoreCode . '/',
        ];
        $mail->setMessageContent('password-restore', $args);
        $mail->send();
    } catch (Exception $e) {

    }

    echo $restoreCode;
} else {
    echo '';
}