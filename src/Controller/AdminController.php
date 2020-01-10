<?php

namespace App\Controller;

use App\Model\AdminModel;
use App\Model\BlogModel;
use App\Model\ListBlogModel;
use App\Model\MainModel;

class AdminController extends MainController
{

    public function defaultMethod()
    {
        $this->isLegitAdmin();
        return $this->twig->render('admin.twig');
    }

    public function listUserMethod()
    {
        $this->isLegitAdmin();

        $req = new AdminModel;
        $req = $req->selectAlluser();

        return $this->twig->render('admin.twig', ['user' => $req]);
    }

    public function listArticleMethod()
    {
        $this->isLegitAdmin();

        $req = new AdminModel();
        $req = $req->selectArticleAdmin();

        return $this->twig->render('admin.twig', ['article' => $req]);
    }

    public function editArticleMethod()
    {
        $this->isLegitAdmin();
        $post = $this->post->getPostArray();
        if (!empty($post)) {
            /** Si $_POST existe et possède des données, les données sont ajoutées à la bdd */
            $edit = new AdminModel();

            $edit->updateArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('admin&method=listarticle');

        } elseif (empty($post)) {
            /**Si $_POST est vide, renvois sur formulaire pour saisir les données à changer **/
            $get = $this->get->getGetVar('idarticle');
            if ($get === false) $this->redirect('admin');

            $req = new BlogModel();
            $req = $req->selectArticle($get);
            return $this->twig->render('adminUpdateArticle.twig', ['article' => $req]);
        }
    }

    public function deleteArticleMethod()
    {
        $this->isLegitAdmin();

        $delete = new AdminModel;
        $delete->deleteArticle($this->get->getGetVar('idarticle'));

        $req = new ListBlogModel();
        $req = $req->selectAllArticle();
//// Delete Comment Article
        return $this->twig->render('admin.twig', ['article' => $req]);
    }

    public function listCommentMethod()
    {
        $this->isLegitAdmin();

        $req = new AdminModel();
        $req = $req->getAllComment();

        return $this->twig->render('admin.twig', ['comment' => $req]);
    }

    public function approveCommentMethod()
    {
        $this->isLegitAdmin();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveComment($get);
        $this->redirect('admin&method=listcomment');
    }

    public function deleteCommentMethod()
    {
        $this->isLegitAdmin();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('admin');

        $req = new AdminModel();
        $req->deleteComment($get);
        $this->redirect('admin&method=listcomment');
    }

    public function approveArticleMethod()
    {
        $this->isLegitAdmin();

        $get = $this->get->getGetVar('idarticle');
        if ($get == false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveArticle($get); // Set article.validated to 1. 0 is non approuved article
        $this->redirect('admin&method=listarticle');
    }

    public function changePasswordMethod()
    {
        $this->isLegitAdmin();

        $post = $this->post->getPostArray();

        if (!empty($post)) {
            if ($post['password1'] === $post['password2']) {
                $password = new AdminModel();
                $pass = $password->getAdminPassword($this->session->getUserVar('id_user'));
                if (password_verify($post['oldpassword'], $pass['password'])) {
                    $new_pass = password_hash($post['password1'], PASSWORD_DEFAULT);
                    $password->changeAdminPassword($new_pass, $this->session->getUserVar('id_user'));
                    return $this->twig->render('admin.twig', ['success' => 'Votre mot de passe a bien été modifié', 'password' => true]);
                }
            }
            return $this->twig->render('admin.twig', ['erreur' => 'Les mots de passes sont différents', 'password' => true]);
        }
        return $this->twig->render('admin.twig', ['password' => true]);
    }


}