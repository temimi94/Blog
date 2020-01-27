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
        $this->isLegit();

        return $this->twig->render('user/user.twig');
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listMyCommentMethod()
    {
        $this->isLegit();

        $req = $this->userSql->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user/user.twig', ['comments' => $req]);
    }


    /**
     *
     */
    public function deleteMyCommentMethod()
    {
        $this->isLegit();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) {

            $this->redirect('user');
        }

        $this->userSql->deleteComment($get);

        $this->redirect('user&method=listMyComment');
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
        $this->isLegit();
        $twigPage = 'user/user.twig';
        $post = $this->post->getPostArray();

        if(empty($post)){
            return $this->twig->render($twigPage, ['password' => true]);
        }

        $change = $this->changePasswordWhenLogged();
        if($change === true){

            return $this->renderTwigSuccess($twigPage, 'Votre mot de passe a bien été modifié');
        }

        return $this->twig->render($twigPage, ['erreur' => $change, 'password' => true]);
    }

    /**
     * @return bool|string
     * Return the error msg if happen or true if the password can be change
     * goto ifError to skip all the conditions if one is true
     */
    public function changePasswordWhenLogged(){

        $post = $this->post->getPostArray();
        $errorMsg = null;

        if($post['password1'] != $post['password2']){
            $errorMsg = 'Les mots de passes sont différents';
            goto ifError;
        }

        $pass = $this->userSql->getUserPassword($this->session->getUserVar('id_user'));

        if(!password_verify($post['oldpassword'], $pass['password'])){
            $errorMsg = 'Votre mot de passe actuel n\'est pas bon';
            goto ifError;
        }

        $newPass = password_hash($post['password1'], PASSWORD_DEFAULT);
        $this->userSql->changeUserPassword($newPass, $this->session->getUserVar('id_user'));
        $errorMsg = true;

        ifError:

        return $errorMsg;
    }


    /**
     *
     */
    public function isLegit()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') {
            $this->redirect('home');
        }
    }

}