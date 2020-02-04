<?php


namespace App\Controller;

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

            return $this->render(self::TWIG, ['success' => 'Votre mot de passe a bien été modifié']);
        }

        return $this->render(self::TWIG, ['erreur' => $change, 'password' => true]);

    }


}