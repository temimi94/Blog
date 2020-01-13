<?php

namespace App\Controller;

use App\Model\UserModel;


/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends MainController
{

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        $this->isLegitUser();
        return $this->twig->render('user/user.twig');
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listCommentMethod()
    {
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user/user.twig', ['comment' => $req]);
    }


    /**
     *
     */
    public function deleteCommentMethod()
    {
        $this->isLegitUser();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('user');

        $req = new UserModel();
        $req->deleteComment($get);
        $this->redirect('user&method=listcomment');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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
                    return $this->twig->render('user/user.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
                }
            }
            return $this->twig->render('user/user.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }
        return $this->twig->render('user/user.twig', ['password' => true]);
    }

    public function isLegitUser()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

}