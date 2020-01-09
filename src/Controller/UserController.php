<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Model\BlogModel;
use App\Model\ListBlogModel;
use App\Model\MainModel;

class UserController extends MainController
{

    public function defaultMethod()
    {
        $this->isLegitUser();
        return $this->twig->render('user.twig');
    }


    public function listCommentMethod()
    {
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user.twig', ['comment' => $req]);
    }


    public function deleteCommentMethod()
    {
        $this->isLegitUser();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('user');

        $req = new UserModel();
        $req->deleteComment($get);
        $this->redirect('user&method=listcomment');
    }

    public function changePasswordMethod()
    {
        $this->isLegitUser();

        $post = $this->post->getPostArray();

        if (!empty($post)) {
            if ($post['password1'] === $post['password2']) {
                $password = new UserModel();
                $pass = $password->getUserPassword($this->session->getUserVar('id_user'));
                if (password_verify($post['oldpassword'], $pass['password'])) {
                    $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
                    $password->changeUserPassword($new_pass, $this->session->getUserVar('id_user'));
                    return $this->twig->render('user.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
                }
            }
            return $this->twig->render('user.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }
        return $this->twig->render('user.twig', ['password' => true]);
    }


    public function isLegitUser()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

}