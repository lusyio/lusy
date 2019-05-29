<?php

namespace PHPMailer\PHPMailer;
require_once 'engine/phpmailer/PHPMailer.php';
require_once 'engine/phpmailer/SMTP.php';


class LusyMailer extends PHPMailer
{
    public $messageContent;
    public function __construct($exceptions = null)
    {
        parent::__construct($exceptions);
        $this->isSMTP();
        $this->Host = 'lusy.io';
        $this->SMTPAuth = true;
        $this->Username = 'info@lusy.io';
        $this->Password = '~g8#@*2HVne2';
        $this->SMTPSecure = 'ssl';
        $this->Port = 465;
        $this->CharSet = 'UTF-8';
        $this->setFrom('info@lusy.io', 'Lusy.io');
    }

    public function setMessageContent($template, $args)
    {
        $language = 'ru';
        ob_start();
        include 'engine/phpmailer/templates/' . $language . '/' . $template . '.php';
        $content = ob_get_clean();

        foreach ($args as $key => $value) {
            if (!is_array($value) && !is_object($value)) {
                $search = '{$' . $key . '}';
                $content = str_replace($search, $value, $content);
            }
        }
        $this->Body = $content;
    }
}
