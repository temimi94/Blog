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

        $req = $this->userSql->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user/user.twig', ['comments' => $req]);
    }


    /**
     *
     */
    public function deleteCommentMethod()
    {
        $this->isLegitUser();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) {

            $this->redirect('user');
        }

        $this->userSql->deleteComment($get);

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
    public function changePasswordMethod() // TODO Déplacer
    {
        $this->isLegitUser();
        $twigPage = 'user/user.twig';
        $post = $this->post->getPostArray();

        if(empty($post)){
            return $this->twig->render($twigPage, ['password' => true]);
        }

        $change = new LoginController(); //TODO A Changer
        $change = $change->changePasswordWhenLogged();
        if($change === true){

            return $this->renderTwigSuccess($twigPage, 'Votre mot de passe a bien été modifié');
        }

        return $this->twig->render($twigPage, ['erreur' => $change, 'password' => true]);
    }

    /**
     *
     */
    public function isLegitUser()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') {
            $this->redirect('home');
        }
    }

}