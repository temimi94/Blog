<?php

namespace App\Controller;

use App\Model\AdminModel;
use App\Model\BlogModel;
use App\Model\ListBlogModel;
use App\Model\MainModel;

class AdminController extends MainController
{

    public function DefaultMethod(){
        $this->isLegitAdmin();

        $main = new MainController;
        return $main->twig->render('admin.twig');
    }

    public function ListuserMethod(){
        $this->isLegitAdmin();

        $req = new MainModel;
        $req = $req->selectAlluser();

        $main = new MainController;
        return $main->twig->render('admin.twig', ['user' => $req]);
    }

    public function ListarticleMethod(){
        $this->isLegitAdmin();

        $req = new AdminModel();
        $req = $req->selectArticleAdmin();

        $main = new MainController;
        return $main->twig->render('admin.twig', ['article' => $req]);
    }

    public function EditarticleMethod(){
        $this->isLegitAdmin();
        $main = new MainController;

        if(!empty($_POST)) { /** Si $_POST existe et possède des données, les données sont ajoutées à la bdd */
            $edit = new AdminModel();
            $post = array_map( 'htmlspecialchars', $_POST); /** Sécurise les données */
            $edit->updateArticle($post['id_article'], $post['title'], $post['chapo'], $post['content']);

            $this->redirect('admin&method=listarticle');

        }elseif(empty($_POST)){ /**Si $_POST est vide, renvois sur formulaire pour saisir les données à changer **/
            $get = filter_input(INPUT_GET, 'idarticle', FILTER_VALIDATE_INT);
            if($get === false) $this->redirect('admin');

            $req = new BlogModel();
            $req = $req->selectArticle($get);
            return $main->twig->render('adminUpdateArticle.twig', ['article' => $req]);
        }
    }

    public function DeletearticleMethod(){
        $this->isLegitAdmin();
        
        $delete = new AdminModel;
        $delete->deleteArticle(filter_input(INPUT_GET, 'idarticle', FILTER_VALIDATE_INT));

        $req = new ListBlogModel();
        $req = $req->selectAllArticle();

        $main = new MainController;
        return $main->twig->render('admin.twig', ['article' => $req]);
    }

    public function ListcommentMethod(){
        $this->isLegitAdmin();

        $req = new AdminModel();
        $req = $req->getAllComment();

        $main = new MainController;
        return $main->twig->render('admin.twig', ['comment' => $req]);
    }

    public function ApprovecommentMethod(){
        $this->isLegitAdmin();

        $get = filter_input(INPUT_GET, 'idcomment', FILTER_VALIDATE_INT);
        if($get ===false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveComment($get);
        $this->redirect('admin&method=listcomment');
    }

    public function DeletecommentMethod(){
        $this->isLegitAdmin();

        $get = filter_input(INPUT_GET, 'idcomment', FILTER_VALIDATE_INT);
        if($get ===false) $this->redirect('admin');

        $req = new AdminModel();
        $req->deleteComment($get);
        $this->redirect('admin&method=listcomment');
    }

    public function ApprovearticleMethod(){
        $this->isLegitAdmin();

        $get = filter_input(INPUT_GET, 'idarticle', FILTER_VALIDATE_INT);
        if($get == false) $this->redirect('admin');

        $req = new AdminModel();
        $req->approveArticle($get); // Set article.validated to 1. 0 is non approuved article
        $this->redirect('admin&method=listarticle');
    }

    public function isLegitAdmin(){
        if($_SESSION['rank'] != 'Administrateur')   {
            $this->redirect('home');
            exit();
        }
    }

}