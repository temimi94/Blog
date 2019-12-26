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

        $main = new MainController;
        return $main->twig->render('user.twig');
    }


    public function ListarticleMethod(){
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->selectArticleUser($_SESSION['id_user']);

        $main = new MainController;
        return $main->twig->render('user.twig', ['article' => $req]);
    }

    public function EditarticleMethod(){
        $this->isLegitUser();
        $main = new MainController;

        if(!empty($_POST)) { /** Si $_POST existe et possède des données, les données sont ajoutées à la bdd */
            $edit = new UserModel();
            $post = array_map( 'htmlspecialchars', $_POST); /** Sécurise les données */
            $edit->updateUserArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('user&method=listarticle');

        }elseif(empty($_POST)){ /**Si $_POST est vide, renvois sur formulaire pour saisir les données à changer **/
            $get = filter_input(INPUT_GET, 'idarticle', FILTER_VALIDATE_INT);
            if($get === false) $this->redirect('user');

            $req = new BlogModel();
            $req = $req->selectArticle($get);
            return $main->twig->render('userUpdateArticle.twig', ['article' => $req]);
        }
    }

    public function DeletearticleMethod(){
        $this->isLegitUser();

        $delete = new UserModel;
        $delete->deleteUserArticle(filter_input(INPUT_GET, 'idarticle', FILTER_VALIDATE_INT), $_SESSION['id_user']);

        $req = $delete->selectArticleUser($_SESSION['id_user']);
//// Delete Comment Article
        $main = new MainController;
        return $main->twig->render('user.twig', ['article' => $req]);
    }

    public function ListcommentMethod(){
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->getUserComment($_SESSION['id_user']);

        $main = new MainController;
        return $main->twig->render('user.twig', ['comment' => $req]);
    }
    

    public function DeletecommentMethod(){
        $this->isLegitUser();

        $get = filter_input(INPUT_GET, 'idcomment', FILTER_VALIDATE_INT);
        if($get ===false) $this->redirect('user');

        $req = new UserModel();
        $req->deleteComment($get);
        $this->redirect('user&method=listcomment');
    }


    public function isLegitUser(){
        if($_SESSION['rank'] != 'Utilisateur') $this->redirect('home');
    }

}