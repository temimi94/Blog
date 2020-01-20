<?php

namespace App\Controller;


use App\Model\AdminModel;
use App\Model\LoginModel;
use App\Model\UserModel;
use DateTime;

/**
 * Class LoginController
 * @package App\Controller
 */
class LoginController extends MainController
{

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function DefaultMethod()
    {
        if ($this->session->isLogged()) $this->redirect('home');
        $view = $this->twig->render('login/login.twig');
        return $view;
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function loginMethod()
    {
        $login = new LoginModel();
        $view = new MainController();
        $post = $this->post->getPostArray();

        if(empty($post['email'] OR empty($post['password']))){
            return $this->twig->render('login/login.twig');
        } //Si $_POST est vide

        if ($login->getUser($post['email']) === false) {
            return $view->twig->render('login/login.twig', ['erreur' => 'Mauvaise adresse mail']);
        }//Si l'adresse email n'est pas bonne

        $data = $login->getUser($post['email']); //Récupère les données de l'utilisateur avec l'email

        if(!password_verify($post['password'], $data['password'])){
            return $view->twig->render('login/login.twig', ['erreur' => 'Mot de passe incorrect']);
        }

        if (isset($post['remember_me'])) {
            $this->session->login($data, true);//login() will redirect to the good home page
        }
        $this->session->login($data, false);


       /*
        if (isset($post['email'])) {
            if ($login->getUser($post['email']) === false) {
                return $view->twig->render('login/login.twig', ['erreur' => 'Mauvaise adresse mail']);
            }
            $data = $login->getUser($post['email']);
            if (password_verify($post['password'], $data['password'])) {
                if (isset($post['remember_me'])) {
                    $this->session->login($data, true);//login() will redirect to the good home page
                } else {
                    $this->session->login($data, false);
                }
            }
            return $view->twig->render('login/login.twig', ['erreur' => 'Mot de passe incorrect']);
        }*/
    }

    /**
     *
     */
    public function logoutMethod()
    {
        $this->session->logout();
        $this->redirect('home');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function registerMethod()
    {
        $post = $this->post->getPostArray();

        if (!empty($post)) {
            if ($post['password1'] != $post['password2']) return $this->twig->render('login/register.twig', ['erreur' => 'Les mots de passes sont différents']);

            $post['password'] = password_hash($post['password1'], PASSWORD_DEFAULT);
            $register = new LoginModel();
            $register->createUser($post['pseudo'], $post['email'], $post['password']);
            $this->redirect('home');
        }
        return $this->twig->render('login/register.twig');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function emailForgetMethod()
    {
        $post = $this->post->getPostVar('email');
        if (!empty($post)) {
            $search = new LoginModel();
            $search = $search->getUser($post); //Vérifie si l'utilisateur est dans la base de données
            if ($search === false) return $this->twig->render('login/forget.twig', ['erreur' => 'Vous n\'avez pas de compte chez nous!']);

            $mail = new MailController();
            $user = array('email' => $search['email'],
                'id_user' => $search['id_user']);
            $mail->sendForgetEmailMethod($user); //Envoi le mail

            if ($mail === false) return $this->twig->render('login/forget.twig', ['erreur' => 'Une erreur est survenue lors de l\'envoi du mail']);
            return $this->twig->render('login/forget.twig', ['success' => 'Nous vous avons envoyé un lien par email. Il sera actif que 15 minutes.']);
        }
        return $this->twig->render('login/forget.twig');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     *
     * First if check if $_POST isn't empty
     * Second if verify is user id find in get match with an user id in the database
     * Third if verify if the token match with user's token
     * Fourth if verify if the token's date isn't passed (15min)
     * Fifth if verify if the passwords are the same
     * Then change password
     *
     * $this->twig->render got 'token' and 'iduser' everytime because it is needed to redirect with the good link
     */
    public function changePasswordMethod()
    {
        $post = $this->post->getPostArray();
        $get = $this->get->getGetArray();

        if(empty($post)){
            return $this->twig->render('login/changepassword.twig', ['token' => $get['token'], 'iduser' => $get['iduser']]);
        }

        $req = new LoginModel();
        $verif = $req->getUserById($get['iduser']);

        if ($verif === false) {
            return $this->twig->render('login/changepassword.twig', ['erreur' => 'Il y a eu une erreur!', 'token' => $get['token'], 'iduser' => $get['iduser']]);
        } //Si l'on ne trouve pas l'utilisateur

        if ($get['token'] != $verif['forgot_token']) {
            return $this->twig->render('login/changepassword.twig', ['erreur' => 'Le token n\'est pas bon!', 'token' => $get['token'], 'iduser' => $get['iduser']]);
        } //Si les token sont différents

        $date_verif = date_create_from_format('Y-m-d H:i:s', $verif['forgot_token_expiration']);
        $date = new DateTime("now");
        if ($date > $date_verif) return $this->twig->render('login/changepassword.twig', ['erreur' => 'Le token a expiré!', 'token' => $get['token'], 'iduser' => $get['iduser']]);
        //Si le token expire

        if ($post['password1'] != $post['password2']){
            return $this->twig->render('login/changepassword.twig', ['erreur' => 'Les mots de passes entrés ne sont pas identiques!', 'token' => $get['token'], 'iduser' => $get['iduser']]);
        }//Si les mots de passes ne sont pas identifiques

        $password = password_hash($post['password1'], PASSWORD_DEFAULT);
        $req->changePassword($password, $get['iduser']);
        return $this->twig->render('login/login.twig', ['success' => 'Votre mot de passe a bien été modifié!', 'token' => $get['token'], 'iduser' => $get['iduser']]);
        //Change le mot de passe et renvois la page login avec un message de succès

    }

    /**
     * @return bool|string
     */
    public function changePassword(){ //Call when logged in user/admin panel
        $post = $this->post->getPostArray();

        if($post['password1'] != $post['password2']){
            return 'Les mots de passes sont différents';
            //return $this->twig->render($road, ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }

        $password = new UserModel();
        $pass = $password->getUserPassword($this->session->getUserVar('id_user'));

        if(!password_verify($post['oldpassword'], $pass['password'])){
            return 'Votre mot de passe actuel n\'est pas bon';
            //return $this->twig->render($road, ['erreur' => 'Votre mot de passe actuel n\'est pas bon', 'password' => true]);
        }

        if(password_verify($post['oldpassword'], $pass['password'])){
            $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
            $password->changeUserPassword($new_pass, $this->session->getUserVar('id_user'));
            return true;
            //return $this->twig->render($road, ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
        }

    }
}