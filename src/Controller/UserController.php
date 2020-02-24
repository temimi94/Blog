<?php

namespace App\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends MainController
{

    /**
     *
     */
    const TWIG = 'user/user.twig';

    /**
     * @return string
     */
    public function defaultMethod()
    {
        $this->session->isUser();

        return $this->render(self::TWIG);
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function changePasswordMethod()
    {
        $this->session->isUser();
        $post = $this->post->getPostArray();

        if (empty($post)) {
            return $this->render(self::TWIG, ['password' => true]);
        }

        $change = $this->changePasswordWhenLogged();
        if ($change === true) {

            return $this->renderTwigSuccess(self::TWIG, 'Votre mot de passe a bien été modifié');
        }

        return $this->render(self::TWIG, ['erreur' => $change, 'password' => true]);
    }

    /**
     * @return bool|string
     * Return the error msg if happen or true if the password can be change
     * goto ifError to skip all the conditions if one is true
     */
    protected function changePasswordWhenLogged()
    {

        $post = $this->post->getPostArray();
        $errorMsg = null;

        if ($post['password1'] != $post['password2']) {
            $errorMsg = 'Les mots de passes sont différents';
            goto ifError;
        }

        $pass = $this->userSql->getUserPassword($this->session->getUserVar('idUser'));

        if (!password_verify($post['oldpassword'], $pass['password'])) {
            $errorMsg = 'Votre mot de passe actuel n\'est pas bon';
            goto ifError;
        }

        $newPass = password_hash($post['password1'], PASSWORD_DEFAULT);
        $this->userSql->changeUserPassword($newPass, $this->session->getUserVar('idUser'));
        $errorMsg = true;

        ifError:

        return $errorMsg;
    }


}