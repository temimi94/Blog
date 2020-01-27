<?php

namespace App\Controller;


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
        $post = $this->post->getPostArray();
        $errorMsg = null;
        $twigPage = 'login/login.twig';

        if(empty($post)){
            return $this->twig->render('login/login.twig');
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
        return $this->renderTwigErr($twigPage, $errorMsg);

    }

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

    public function emailForgetMethod(){
        $post = $this->post->getPostVar('email');
        $twigPage = 'login/forget.twig';

        if(empty($post)){
            return $this->twig->render($twigPage);
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

        return $this->renderTwigSuccess($twigPage, 'Nous vous avons envoyé un lien par email. Il ne sera actif que 15 minutes.');

        ifError:
        return $this->renderTwigErr($twigPage, $errorMsg);
    }

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
        $twigPage = 'login/register.twig';

        if(empty($post)){
            return $this->twig->render('login/register.twig');
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

        return $this->renderTwigErr($twigPage, $errorMsg);

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
        $twigPage = 'login/changepassword.twig';
        $errorMsg = null;

        if(empty($post)){
            return $this->twig->render($twigPage);
        }//Affiche la page si le formulaire n'est pas complété

        $verifPost = $this->post->verifyPost();
        if($verifPost !== true) {
            $errorMsg = $verifPost;
            goto ifError;
        }//Si le mot de passe est trop court (5 caractères) null ou vide

        $verif = $this->loginSql->getUserById($get['iduser']);

        if ($verif === false) {
            $errorMsg = 'Il y a eu une erreur!';
            goto ifError;
        } //Si l'on ne trouve pas l'utilisateur

        $verification = $this->verifyToken($verif['forgot_token'], $verif['forgot_token_expiration'], $get['token']);
        if($verification !== true) {
            $errorMsg = $verification;
            goto ifError;
        }

        if ($post['password1'] != $post['password2']){
            $errorMsg = 'Les mots de passes entrés ne sont pas identiques';
            goto ifError;
        }//Si les mots de passes ne sont pas identifiques

        $password = password_hash($post['password1'], PASSWORD_DEFAULT);
        $this->loginSql->changePassword($password, $get['iduser']);

        return $this->renderTwigSuccess('login/login.twig', 'Votre mot de passe a bien été modifié');
        //Change le mot de passe et renvois la page login avec un message de succès

        ifError:

        return $this->renderTwigErr($twigPage, $errorMsg);

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

    /**
     * @return bool|string
     * Return the error msg if happen or true if the password can be change
     * goto ifError to skip all the conditions if one is true
     */
    /*
    public function changePasswordWhenLogged(){ //Call when logged in user/admin panel

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
****/
}