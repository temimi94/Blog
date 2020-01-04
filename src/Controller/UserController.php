<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Model\BlogModel;
use App\Model\ListBlogModel;
use App\Model\MainModel;

class UserController extends MainController
{

    public function DefaultMethod(){
        $this->isLegitUser();


        return $this->twig->render('user.twig');
    }


    public function ListarticleMethod(){
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->selectArticleUser($this->session->getUserVar('id_user'));

        return $this->twig->render('user.twig', ['article' => $req]);
    }

    public function EditarticleMethod(){
        $this->isLegitUser();
        $post = $this->post->getPostArray();

        if(!empty($post)) { /** Si $_POST existe et possède des données, les données sont ajoutées à la bdd */
            $edit = new UserModel();

            $edit->updateUserArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('user&method=listarticle');

        }elseif(empty($post)){ /**Si $_POST est vide, renvois sur formulaire pour saisir les données à changer **/
            $get = $this->get->getGetVar('idarticle');
            if($get === false) $this->redirect('user');

            $req = new BlogModel();
            $req = $req->selectArticle($get);
            return $this->twig->render('userUpdateArticle.twig', ['article' => $req]);
        }
    }

    public function DeletearticleMethod(){
        $this->isLegitUser();

        $delete = new UserModel;
        $delete->deleteCommentByArticle($this->get->getGetVar('idarticle'));
        $delete->deleteUserArticle($this->get->getGetVar('idarticle'), $this->session->getUserVar('id_user'));

        $req = $delete->selectArticleUser($this->session->getUserVar('id_user'));
        return $this->twig->render('user.twig', ['article' => $req]);
    }

    public function ListcommentMethod(){
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user.twig', ['comment' => $req]);
    }
    

    public function DeletecommentMethod(){
        $this->isLegitUser();

        $get = $this->get->getGetVar('idcomment');
        if($get ===false) $this->redirect('user');

        $req = new UserModel();
        $req->deleteComment($get);
        $this->redirect('user&method=listcomment');
    }


    public function isLegitUser(){
        if($this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

}