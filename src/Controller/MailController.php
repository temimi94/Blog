<?php

namespace App\Controller;


use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailController extends MainController
{
    
    public function sendEmail(array $user)
    {
        require_once('../config/setupMail.php'); //$configMail dans le dossier setupMail.php

        // Create the Transport
        $transport = (new Swift_SmtpTransport($configMail['smtp'],$configMail['port']))
            ->setUsername($configMail['username'])
            ->setPassword($configMail['password']);

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('Contact Blog PHP P5 OpenClassrooms'))
            ->setFrom([$user['email'] => $user['name']])
            ->setTo('kinder.theo@gmail.com')
            ->setBody($user['content']);

        // Send the message
        $result = $mailer->send($message);
        return $result;
    }
}