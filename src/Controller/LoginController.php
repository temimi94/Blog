<?php

namespace App\Controller;


use DateTime;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class LoginController
 * @package App\Controller
 */
class LoginController extends MainController
{
    /**
     *
     */
    const TWIG_LOGIN = 'login/login.twig';
    /**
     *
     */
    const TWIG_FORGET = 'login/forget.twig';
    /**
     *
     */
    const TWIG_REGISTER = 'login/register.twig';
    /**
     *
     */
    const TWIG_CHANGEPASS = 'login/changepassword.twig';
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function DefaultMethod()
    {
        if ($this->session->isLogged()) {
            $this->redirect('home');
        }
        return $this->render(self::TWIG_LOGIN);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function loginMethod()
    {
        $post = $this->post->getPostArray();
        $errorMsg = null;

        if(empty($post)){
            return $this->twig->render(self::TWIG_LOGIN);
        } //Si $_POST est vide

        $data = $this->loginSql->getUser($post['email']); //Récupère les données de l'utilisateur avec l'email

        if ($data === false) {
            $errorMsg = 'Mauvaise adresse mail';
            goto ifError;
        }//Si l'adresse email n'est pas bonne


        if(!password_verify($post['password'], $data['password'])){
            $errorMsg = "Mot de passe incorrect";
            goto ifError;
        }


        $this->auth($data);

        ifError:
        return $this->renderTwigErr(self::TWIG_LOGIN, $errorMsg);

    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function auth($data = [])
    {
        $this->session->createSession($data);
        if ($this->session->getUserVar('rank') === 'Administrateur'){
            $this->redirect('admin');
        }
        elseif ($this->session->getUserVar('rank') === 'Utilisateur'){
            $this->redirect('user');
        }
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * Forgot password method on login page
     */
    public function passwordForgetMethod(){
        $post = $this->post->getPostVar('email');

        if(empty($post)){
            return $this->twig->render(self::TWIG_FORGET);
        }//Affiche le formulaire si $_POST est vide

        $search = $this->loginSql->getUser($post);
        if ($search === false){
            $errorMsg = 'Vous n\'avez pas de compte chez nous';
            goto ifError;
        }//Vérifie si l'utilisateur est dans la base de données

        $user = array('email' => $search['email'],
            'idUser' => $search['idUser']);
        $mail = $this->mail->sendForgetPassword($user); //Envoi le mail

        if ($mail === false){
            $errorMsg = 'Une erreur est survenue lors de l\'envoi du mail';
            goto ifError;
        }//Si il y a une erreur dans l'envoi du mail

        return $this->renderTwigSuccess(self::TWIG_FORGET, 'Nous vous avons envoyé un lien par email. Il ne sera actif que 15 minutes.');

        ifError:
        return $this->renderTwigErr(self::TWIG_FORGET, $errorMsg);
    }

    /**
     *
     */
    public function logoutMethod()
    {
        unset($_SESSION);
        session_destroy();
        $this->redirect('home');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function registerMethod()
    {
        $post = $this->post->getPostArray();


        if(empty($post)){
            return $this->twig->render(self::TWIG_REGISTER);
        }

        $verif = $this->post->verifyPost();
        if($verif !== true){
            $errorMsg = $verif;
            goto ifError;
        }

        if ($post['password1'] != $post['password2']) {
            $errorMsg = 'Les mots de passe sont différents';
            goto ifError;
        }

        $post['password'] = password_hash($post['password1'], PASSWORD_DEFAULT);

        $this->loginSql->createUser($post['pseudo'], $post['email'], $post['password']);

        return $this->renderTwigSuccess('home.twig', 'Votre compte a bien été créer');

        ifError:

        return $this->renderTwigErr(self::TWIG_REGISTER, $errorMsg);

    }

    /**
     * @return string
     *
     * First if check if $_POST isn't empty
     * Second if verify is user id find in get match with an user id in the database
     * Third if verify if the token match with user's token
     * Fourth if verify if the token's date isn't passed (15min)
     * Fifth if verify if the passwords are the same
     * Then change password
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function changePasswordByMailMethod() //Change Password when password forgotten mail
    {
        $post = $this->post->getPostArray();
        $get = $this->get->getGetArray();

        if(empty($post)){

            return $this->twig->render(self::TWIG_CHANGEPASS);
        }//Affiche la page si le formulaire n'est pas complété

        $verify = $this->verifyChangePasswordByMail($get, $post);
        if($verify !== true){

            return $this->renderTwigErr(self::TWIG_CHANGEPASS, $verify);
        }

        $password = password_hash($post['password1'], PASSWORD_DEFAULT);
        $this->loginSql->changePassword($password, $get['idUser']);


        return $this->renderTwigSuccess(self::TWIG_LOGIN, 'Votre mot de passe a bien été modifié');
    }

    /**
     * @param array $get
     * @param array $post
     * @return bool|string
     * @throws Exception
     */
    private function verifyChangePasswordByMail(array $get, array $post){ //Return a string error message or return true
        $verifyPost = $this->post->verifyPost();
        if($verifyPost !== true) {

            return $verifyPost;
        }//Si le mot de passe est trop court (5 caractères) null ou vide

        $verify = $this->loginSql->getUserById($get['idUser']);

        if ($verify === false) {

            return "Il y a eu une erreur!";
        } //Si l'on ne trouve pas l'utilisateur

        $verification = $this->verifyToken($verify['forgotToken'], $verify['forgotTokenExpiration'], $get['token']);
        if($verification !== true) {

            return $verification;
        } //Vérifie le token

        if ($post['password1'] != $post['password2']){

            return "Les mots de passe entrés ne sont pas identiques";
        }//Si les mots de passes ne sont pas identiques

        return true;
    }

    /**
     * @param $tokenInDb
     * @param $tokenInDbDate
     * @param $currentToken
     * @return bool|string
     * @throws Exception
     */
    private function verifyToken(string $tokenInDb, string $tokenInDbDate, string $currentToken){
        $tokenInDbDate = date_create_from_format('Y-m-d H:i:s', $tokenInDbDate);
        $date = new DateTime("now");
        if($tokenInDb != $currentToken){

            return 'Le token n\'est pas bon!';
        }
        elseif($date > $tokenInDbDate){

            return 'Le token a expiré!';
        }
        else{

            return true;
        }
    }

}