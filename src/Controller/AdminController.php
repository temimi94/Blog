<?php

namespace App\Controller;

use App\Model\AdminModel;
use App\Model\BlogModel;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends MainController
{

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        $this->isLegitAdmin();
        return $this->twig->render('admin/admin.twig');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listUserMethod()
    {
        $this->isLegitAdmin();;

        $req = new AdminModel;
        $req = $req->selectAlluser();

        return $this->twig->render('admin/admin.twig', ['user' => $req]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listArticleMethod()
    {
        $this->isLegitAdmin();;

        $req = new AdminModel();
        $req = $req->selectArticleAdmin();

        return $this->twig->render('admin/admin.twig', ['article' => $req]);
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function editArticleMethod()
    {
        $this->isLegitAdmin();;
        $post = $this->post->getPostArray();
        if (!empty($post)) {
            /* Si $_POST existe et possède des données, les données sont ajoutées à la bdd */
            $edit = new AdminModel();

            $edit->updateArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('admin&method=listarticle');

        } elseif (empty($post)) {
            /*Si $_POST est vide, renvois sur formulaire pour saisir les données à changer */
            $get = $this->get->getGetVar('idarticle');
            if ($get === false) $this->redirect('admin');

            $req = new BlogModel();
            $req = $req->selectArticle($get);
            return $this->twig->render('admin/adminUpdateArticle.twig', ['article' => $req]);
        }
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function deleteArticleMethod()
    {
        $this->isLegitAdmin();;

        $delete = new AdminModel;
        $id_article = $this->get->getGetVar('idarticle');
        $delete->deleteArticle($id_article);

        $this->redirect('admin&method=listArticle');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listCommentMethod()
    {
        $this->isLegitAdmin();;

        $req = new AdminModel();
        $req = $req->getAllComment();

        return $this->twig->render('admin/admin.twig', ['comment' => $req]);
    }

    /**
     *
     */
    public function approveCommentMethod()
    {
        $this->isLegitAdmin();;

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveComment($get);
        $this->redirect('admin&method=listcomment');
    }

    /**
     *
     */
    public function deleteCommentMethod()
    {
        $this->isLegitAdmin();;

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $req = new AdminModel();
        $req->deleteComment($get);
        $this->redirect('admin&method=listcomment');
    }

    /**
     *
     */
    public function approveArticleMethod()
    {
        $this->isLegitAdmin();;

        $get = $this->get->getGetVar('idarticle');
        if ($get == false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveArticle($get); // Set article.validated to 1. 0 is non approuved article
        $this->redirect('admin&method=listarticle');
    }

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function changeAdminPasswordMethod()
    {
        $this->isLegitAdmin();;

        $post = $this->post->getPostArray();

        if(empty($post)){
            return $this->twig->render('admin/admin.twig', ['password' => true]);
        }

        if($post['password1'] != $post['password2']){
            return $this->twig->render('admin/admin.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }

        $password = new AdminModel();
        $pass = $password->getAdminPassword($this->session->getUserVar('id_user'));

        if(!password_verify($post['oldpassword'], $pass['password'])){
            return $this->twig->render('admin/admin.twig', ['erreur' => 'Votre mot de passe actuel n\'est pas bon', 'password' => true]);
        }

        if(password_verify($post['oldpassword'], $pass['password'])){
            $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
            $password->changeAdminPassword($new_pass, $this->session->getUserVar('id_user'));
            return $this->twig->render('admin/admin.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
        }
/*
        if (!empty($post)) {
            if ($post['password1'] === $post['password2']) {
                $password = new AdminModel();
                $pass = $password->getAdminPassword($this->session->getUserVar('id_user'));
                if (password_verify($post['oldpassword'], $pass['password'])) {
                    $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
                    $password->changeAdminPassword($new_pass, $this->session->getUserVar('id_user'));
                    return $this->twig->render('admin/admin.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
                }
            }elseif(!$post['password1'] === $post['password2']) {
                return $this->twig->render('admin/admin.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
            }
        }
        return $this->twig->render('admin/admin.twig', ['password' => true]); */
    }

    /**
     *
     */
    public function isLegitAdmin()
    {
        if ($this->session->getUserVar('rank') != 'Administrateur') {
            $this->redirect('home');
        }
    }


}