<?php

namespace App\service;

use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendMail
{
    public function __construct(\Swift_Mailer $mailer){
    $this->mailer = $mailer;
    }

    public function send($destination,$subject,$message){
        $email = (new \Swift_Message($subject))
            ->setFrom('moutarndiath@gamil.com', 'NDH')
            ->setTo($destination)
            ->setBody($message);
        $this->mailer->send($email);
    }
}