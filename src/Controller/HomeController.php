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
        $twigPage = 'home.twig';
        $verif = $this->post->verifyPost();

        if ($verif !== true) {

            return $this->renderTwigErr($twigPage, $verif);
        }

        $post = $this->post->getPostArray();

        $mail = $this->mail->sendContactEmail($post);

        if ($mail == true) {

            return $this->renderTwigSuccess($twigPage, 'Votre message nous a bien été transmis, je vous répondrais au plus tôt!');
        }

        return $this->renderTwigErr($twigPage, 'Une erreur s\'est produite lors de l\'envoi du mail');
    }

}