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
     * First if verify if $_POST isn't empty
     * Second if verify if the passwords entered are the same
     * Third if verify if the actual password is the same is in the database
     * Then fourth if change password the actual password with the new one
     * All ifs render the twig page with an error message or a success message
     */
    public function changePasswordMethod()
    {
        $this->isLegitUser();

        $post = $this->post->getPostArray();

        if(empty($post)){
            return $this->twig->render('user/user.twig', ['password' => true]);
        }
        $change = new LoginController();
        $change = $change->changePassword();
        if($change === true){
            return $this->twig->render('user/user.twig', ['success' => 'Votre mot de passe a bien été modifié']);
        }
        return $this->twig->render('user/user.twig', ['erreur' => $change, 'password' => true]);

        /*

        if(empty($post)){
            return $this->twig->render('user/user.twig', ['password' => true]);
        }

        if($post['password1'] != $post['password2']){
            return $this->twig->render('user/user.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }

        $password = new UserModel();
        $pass = $password->getUserPassword($this->session->getUserVar('id_user'));

        if(!password_verify($post['oldpassword'], $pass['password'])){
            return $this->twig->render('user/user.twig', ['erreur' => 'Votre mot de passe actuel n\'est pas bon', 'password' => true]);
        }

        if(password_verify($post['oldpassword'], $pass['password'])){
            $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
            $password->changeUserPassword($new_pass, $this->session->getUserVar('id_user'));
            return $this->twig->render('user/user.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
        }
/*
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
        return $this->twig->render('user/user.twig', ['password' => true]); */
    }

    public function isLegitUser()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

}