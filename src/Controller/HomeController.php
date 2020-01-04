<?php

namespace App\Controller;


class HomeController extends MainController {

    public function DefaultMethod(){
        return $this->twig->render('home.twig');
    }

    public function MailcontactMethod(){
        $mail = new MailController();
        $post = $this->post->getPostArray();
        return $mail->sendMailContact($post['content'], $post['name'], $post['email']);

    }



}