<?php

namespace App\Controller;


use DateTime;

/**
 * Class LoginController
 * @package App\Controller
 */
class LoginController extends MainController
{
    /**
     *
     */
    const TWIGLOGIN = 'login/login.twig';
    /**
     *
     */
    const TWIGFORGET = 'login/forget.twig';
    /**
     *
     */
    const TWIGREGISTER = 'login/register.twig';
    /**
     *
     */
    const TWIGCHANGEPASS = 'login/changepassword.twig';
    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function DefaultMethod()
    {
        if ($this->session->isLogged()) $this->redirect('home');
        $view = $this->twig->render(self::TWIGLOGIN);
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
        $post = $this->post->getPostArray();
        $errorMsg = null;

        if(empty($post)){
            return $this->twig->render(self::TWIGLOGIN);
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

        if (isset($post['remember_me'])) {
            $this->auth($data, true);//login() will redirect to the good home page
        }
        $this->auth($data, false);

        ifError:
        return $this->renderTwigErr(self::TWIGLOGIN, $errorMsg);

    }

    /**
     * @param array $data
     * @param null $remember_me
     * @throws \Exception
     */
    private function auth($data = [], $remember_me = null)
    {
        if ($remember_me == true) {
            $auth_token = bin2hex(openssl_random_pseudo_bytes(32));
            $this->loginSql->createAuthToken($auth_token, $data['id_user']);
            $this->cookie->createCookie('gtk', $auth_token, time()+604800);//Create cookie that expires in 1 week
        }
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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function emailForgetMethod(){
        $post = $this->post->getPostVar('email');

        if(empty($post)){
            return $this->twig->render(self::TWIGFORGET);
        }//Affiche le formulaire si $_POST est vide

        $search = $this->loginSql->getUser($post);
        if ($search === false){
            $errorMsg = 'Vous n\'avez pas de compte chez nous';
            goto ifError;
        }//Vérifie si l'utilisateur est dans la base de données

        $user = array('email' => $search['email'],
            'id_user' => $search['id_user']);
        $mail = $this->mail->sendForgetPassword($user); //Envoi le mail

        if ($mail === false){
            $errorMsg = 'Une erreur est survenue lors de l\'envoi du mail';
            goto ifError;
        }//Si il y a une erreur dans l'envoi du mail

        return $this->renderTwigSuccess(self::TWIGFORGET, 'Nous vous avons envoyé un lien par email. Il ne sera actif que 15 minutes.');

        ifError:
        return $this->renderTwigErr(self::TWIGFORGET, $errorMsg);
    }

    /**
     *
     */
    public function logoutMethod()
    {
        unset($_SESSION);
        session_destroy();
        $this->cookie->destroyCookie('gtk');
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


        if(empty($post)){
            return $this->twig->render(self::TWIGREGISTER);
        }

        $verif = $this->post->verifyPost();
        if($verif !== true){
            $errorMsg = $verif;
            goto ifError;
        }

        if ($post['password1'] != $post['password2']) {
            $errorMsg = 'Les mots de passes sont différents';
            goto ifError;
        }

        $post['password'] = password_hash($post['password1'], PASSWORD_DEFAULT);

        $this->loginSql->createUser($post['pseudo'], $post['email'], $post['password']);

        return $this->renderTwigSuccess('home.twig', 'Votre compte a bien été créer');

        ifError:

        return $this->renderTwigErr(self::TWIGREGISTER, $errorMsg);

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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Exception
     */
    public function changePasswordByMailMethod() //Change Password when password forgotten mail
    {
        $post = $this->post->getPostArray();
        $get = $this->get->getGetArray();
        $errorMsg = null;

        if(empty($post)){

            return $this->twig->render(self::TWIGCHANGEPASS);
        }//Affiche la page si le formulaire n'est pas complété


        if($this->verifyChangePasswordByMail($get, $post) !== true){

            return $this->renderTwigErr(self::TWIGCHANGEPASS, $this->verifyChangePasswordByMail($get, $post));
        }

        $password = password_hash($post['password1'], PASSWORD_DEFAULT);
        $this->loginSql->changePassword($password, $get['iduser']);


        return $this->renderTwigSuccess(self::TWIGLOGIN, 'Votre mot de passe a bien été modifié');



    }

    /**
     * @param array $get
     * @param array $post
     * @return bool|string
     * @throws \Exception
     */
    private function verifyChangePasswordByMail(array $get, array $post){ //Return a string error message or return true
        $verifyPost = $this->post->verifyPost();
        if($verifyPost !== true) {

            return $verifyPost;
        }//Si le mot de passe est trop court (5 caractères) null ou vide

        $verify = $this->loginSql->getUserById($get['iduser']);

        if ($verify === false) {

            return "Il y a eu une erreur!";
        } //Si l'on ne trouve pas l'utilisateur

        $verification = $this->verifyToken($verify['forgot_token'], $verify['forgot_token_expiration'], $get['token']);
        if($verification !== true) {

            return $verification;
        } //Vérifie le token

        if ($post['password1'] != $post['password2']){

            return "Les mots de passes entrés ne sont pas identiques";
        }//Si les mots de passes ne sont pas identiques

        return true;
    }

    /**
     * @param $tokenInDb
     * @param $tokenInDbDate
     * @param $currentToken
     * @return bool|string
     * @throws \Exception
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