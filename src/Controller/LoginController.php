<?php

namespace App\Controller;


use App\Model\LoginModel;

class LoginController extends MainController
{

    public function defaultMethod()
    {


        if ($this->isLogged()) $this->redirect('home');
        $view = $this->twig->render('login.twig');
        return $view;
    }

    /**Create password with password_hash('string', PASSWORD_DEFAULT);**/

    public function loginMethod()
    {

        $login = new LoginModel();
        $view = new MainController();
        $post = $this->post->getPostArray();

        if (isset($post['email'])) {
            if ($login->getUser($post['email']) === false) {
                $err = ['erreur' => 'Mauvaise adresse mail'];
                return $view->twig->render('login.twig', ['erreur' => $err]);
            }
            $data = $login->getUser($post['email']);
            if (password_verify($post['password'], htmlspecialchars($data['password']))) {
                $sess = new SessionController();
                $sess->login($data); //Ajouter remember me
            }
            $err = ['erreur' => 'Mot de passe incorrect'];
            return $view->twig->render('login.twig', ['erreur' => $err]);

        }
        $this->redirect('home');

    }

    public function logoutMethod()
    {
        $logout = new SessionController();
        $logout->logout();
        $this->redirect('home');
    }

    public function registerMethod()
    {
        $post = $this->post->getPostArray();

        if (!empty($post)) {
            if ($post['password1'] != $post['password2']) return $this->twig->render('register.twig', ['erreur' => 'Les mots de passes sont différents']);

            $post['password'] = password_hash($post['password1'], PASSWORD_DEFAULT);
            $register = new LoginModel();
            $register->createUser($post['pseudo'], $post['email'], $post['password']);
            $this->redirect('home');
        }
        return $this->twig->render('register.twig');
    }

    public function forgetMethod()
    {
        $post = $this->post->getPostArray();
        if (!empty($post)) {
            $mail = new MailController();
            $mail->sendForgetMail();
        }
        return $this->twig->render('forget.twig');
    }
}