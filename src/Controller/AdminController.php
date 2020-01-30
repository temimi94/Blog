<?php


namespace App\Controller;


/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends UserController {

    /**
     *
     */
    const TWIG = 'admin/admin.twig';

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        $this->isLegit();
        return $this->twig->render(self::TWIG);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listUserMethod(){
        $this->isLegit();;

        $req = $this->adminSql->selectAlluser();

        return $this->twig->render(self::TWIG, ['user' => $req]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listArticleMethod()
    {
        $this->isLegit();;

        $req = $this->adminSql->selectArticleAdmin();

        return $this->twig->render(self::TWIG, ['article' => $req]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editArticleMethod()
    {
        $this->isLegit();
        $post = $this->post->getPostArray();
        if (!empty($post)) {
            /* Si $_POST existe et possède des données, les données sont ajoutées à la bdd */

            $this->adminSql->updateArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('admin&method=listArticle');

        } elseif (empty($post)) {
            /*Si $_POST est vide, renvois sur formulaire pour saisir les données à changer */
            $get = $this->get->getGetVar('idarticle');
            if ($get === false){
                $this->redirect('admin');
            }

            $req = $this->blogSql->selectArticle($get);

            return $this->twig->render('admin/adminUpdateArticle.twig', ['article' => $req]);
        }
    }

    /**
     *
     */
    public function deleteArticleMethod()
    {
        $this->isLegit();;

        $id_article = $this->get->getGetVar('idarticle');
        $this->adminSql->deleteArticle($id_article);

        $this->redirect('admin&method=listArticle');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listAllCommentMethod()
    {
        $this->isLegit();;

        $req = $this->adminSql->getAllComment();

        return $this->twig->render(self::TWIG, ['comment' => $req]);
    }

    /**
     *
     */
    public function approveCommentMethod()
    {
        $this->isLegit();;

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $this->adminSql->approveComment($get);
        $this->redirect('admin&method=listComment');
    }

    /**
     *
     */
    public function deleteCommentMethod()
    {
        $this->isLegit();;

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $this->adminSql->deleteComment($get);
        $this->redirect('admin&method=listComment');
    }

    /**
     *
     */
    public function approveArticleMethod()
    {
        $this->isLegit();;

        $get = $this->get->getGetVar('idarticle');
        if ($get == false) $this->redirect('admin');

        $this->adminSql->approveArticle($get); // Set article.validated to 1. 0 is non approuved article
        $this->redirect('admin&method=listArticle');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function changePasswordMethod()
    {

        $this->isLegit();

        $post = $this->post->getPostArray();
        if(empty($post)){

            return $this->twig->render(self::TWIG, ['password' => true]);
        }

        $change = $this->changePasswordWhenLogged();
        if($change === true){

            return $this->twig->render(self::TWIG, ['success' => 'Votre mot de passe a bien été modifié']);
        }

        return $this->twig->render(self::TWIG, ['erreur' => $change, 'password' => true]);

    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createArticleMethod()
    {

        $this->isLegit();

        $post = $this->post->getPostArray();

        if (!empty($post)) {
            $verif = $this->post->verifyPost();
            if($verif !== true) {

                return $this->twig->render('createblog.twig', ['erreur' => $verif]);
            }
            $this->blogSql->createArticle($post['title'], $post['content'], $post['chapo'], $this->session->getUserVar('id_user'));

            return $this->renderTwigSuccess('createblog.twig', 'Votre article nous a bien été envoyé! Il ne manque plus qu\'à le valider!');
        } elseif(empty($post)) {

            return $this->twig->render('createblog.twig');
        }
        //$this->redirect('blog&method=createArticle');
    }


    /**
     *
     */
    public function isLegit()
    {
        if ($this->session->getUserVar('rank') !== 'Administrateur') {
            $this->redirect('home');
        }
    }


}