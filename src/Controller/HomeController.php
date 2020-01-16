<?php

namespace App\Controller;


/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends MainController
{

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        return $this->twig->render('home.twig');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendMailMethod()
    {
        $mail = new MailController();

        $verif = $this->post->verifyPost();
        if ($verif !== true) return $this->twig->render('home.twig', ['erreur' => $verif]);
        $post = $this->post->getPostArray();
        $mail->sendContactEmail($post);
        if ($mail == true) return $this->twig->render('home.twig', ['success' => 'Votre message nous a bien été transmis, je vous répondrais au plus tôt!']);
        return $this->twig->render('home.twig', ['erreur' => 'Une erreur s\' est produite lors de l\'envoi du mail']);
    }

}