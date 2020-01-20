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
class MailController extends MainController
{
    /**
     *
     */
    public function defaultMethod(){
        $this->redirect('home');
    }

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
            ->setTo('kinder.theo@gmail.com')
            ->setBody($user['content']);

        // Send the message
        $result = $mailer->send($message);
        return $result;
    }

    /**
     * @param array $user
     * @return int
     */

    public function sendForgetEmailMethod(array $user){ // $user contient user_id & email

        require_once('../config/setupMail.php'); //MAIL Const

        $token = bin2hex(openssl_random_pseudo_bytes(24));
        $token_req = new LoginModel();
        $id_user = $user['id_user'];
        $token_req->createForgotToken($token, $id_user);

        $link = "www.". filter_input(INPUT_SERVER, 'HTTP_HOST') . "/index.php?page=login&method=changePassword&token=" . $token . "&iduser=" .$id_user;

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
            ->setTo('kinder.theo@gmail.com') //change to $user['email']
            ->setBody($content, 'text/html');

        // Send the message
        $result = $mailer->send($message);
        return $result; /** return false if mail not send */
    }
}