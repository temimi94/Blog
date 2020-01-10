<?php

namespace App\Controller;


class HomeController extends MainController
{

    public function defaultMethod()
    {
        return $this->twig->render('home.twig');
    }

    public function sendMailMethod()
    {
        $mail = new MailController();
        $post = $this->post->getPostArray();
        $verif = $this->verifyPost($post);
        if ($verif !== true) return $this->twig->render('home.twig', ['erreur' => $verif]);
        $mail->sendContactEmail($post);
        if($mail == true) return $this->twig->render('home.twig', ['success' => 'Votre message nous a bien été transmis, je vous répondrais au plus tôt!']);
        return $this->twig->render('home.twig', ['erreur' => 'Une erreur s\' est produite lors de l\'envoi du mail']);
    }

    private function verifyPost($post){
        if(empty($post['content'])){
            return 'Il vous manque un message!';
        }elseif(empty($post['name'])){
            return 'Vous avez oublié votre nom!';
        }elseif(empty($post['email'])){
            return 'Vous avez oublié votre email!';
        }
        return true;

    }


}