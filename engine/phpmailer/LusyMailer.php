<?php

namespace PHPMailer\PHPMailer;
require_once 'engine/phpmailer/PHPMailer.php';
require_once 'engine/phpmailer/SMTP.php';


class LusyMailer extends PHPMailer
{
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);
        $this->isSMTP();
        $this->Host = 'smtp.yandex.ru';
        $this->SMTPAuth = true;
        $this->Username = 'lustest02';
        $this->Password = 'mLus280519';
        $this->SMTPSecure = 'ssl';
        $this->Port = 465;
        $this->CharSet = 'UTF-8';
        $this->setFrom('lustest02@yandex.ru'); // Ваш Email
    }
}
