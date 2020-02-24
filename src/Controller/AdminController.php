<?php


namespace App\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends UserController
{

    /**
     *
     */
    const TWIG = 'admin/admin.twig';


    /**
     * @return string
     */
    public function defaultMethod()
    {
        $this->session->isAdmin();

        return $this->render(self::TWIG);
    }

    /**
     * @return string
     */
    public function listUserMethod()
    {
        $this->session->isAdmin();

        $req = $this->adminSql->selectAlluser();

        return $this->render(self::TWIG, ['user' => $req]);
    }


    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function changePasswordMethod()
    {
        $this->session->isAdmin();

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

//TODO Valider un utilisateur
}