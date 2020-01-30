<?php

namespace App\Controller;


use App\Model\LoginModel;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class MailController
 * @package App\Controller
 */
class MailController
{
    /**
     * @param array $user
     * @return int
     */
    public function sendContactEmail(array $user)
    {
        require_once('../config/setupMail.php'); //MAIL Const

        // Create the Transport
        $transport = (new Swift_SmtpTransport(MAIL_SMTP, MAIL_PORT))
            ->setUsername(MAIL_USERNAME)
            ->setPassword(MAIL_PASSWORD);

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $message = (new Swift_Message('Contact Blog PHP P5 OpenClassrooms'))
            ->setFrom([$user['email'] => $user['name']])
            ->setTo(MAIL_USERNAME)
            ->setBody($user['content']);

        // Send the message
        $result = $mailer->send($message);
        return $result;
    }

    /**
     * @param array $user
     * @return int
     * @throws \Exception
     */

    public function sendForgetPassword(array $user){ // $user contient user_id & email

        require_once('../config/setupMail.php'); //MAIL Const

        $token = bin2hex(openssl_random_pseudo_bytes(24));
        $tokenReq = new LoginModel();
        $id_user = $user['id_user'];
        $tokenReq->createForgotToken($token, $id_user);

        $link = "www.". filter_input(INPUT_SERVER, 'HTTP_HOST') . "/index.php?page=login&method=changePasswordByMail&token=" . $token . "&iduser=" .$id_user;

        // Create the Transport
        $transport = (new Swift_SmtpTransport(MAIL_SMTP,MAIL_PORT))
            ->setUsername(MAIL_USERNAME)
            ->setPassword(MAIL_PASSWORD);

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        $content = sprintf("<p>Bonjour !</p>
        <p>Vous avez demandé un changement de mot de passe</p>
        <p>Merci de suivre ce lien pour procéder au changement <a href='%s'>Réinitialiser</a></p>
        <p>Le lien ne sera valide que 15 minutes</p>", $link);
        // Create a message
        $message = (new Swift_Message('Mot de passe oublié Blog PHP P5 Kinder Théo'))
            ->setFrom(MAIL_USERNAME)
            ->setTo($user['email']) //change to $user['email']
            ->setBody($content, 'text/html');

        // Send the message
        $result = $mailer->send($message);
        return $result; /** return false if mail not send */
    }


}