<?php

namespace App\Controller;

use App\Model\BlogModel;
use App\Model\MainModel;

class BlogController extends MainController {



    public function DefaultMethod(){
        $id_blog = self::getId();

        $BlogModel = new BlogModel;
        $blog = $BlogModel->selectArticle($id_blog);
        $comment = $BlogModel->selectCommentByArticle($id_blog);

        $view = $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment]);
        return $view;
    }

    private static function getId(){
        /* Filtre si c'est un entier */
        $id_blog = filter_input(INPUT_GET, 'idblog', FILTER_VALIDATE_INT);

        /*Redirection si la variable est vide*/
        if(!$id_blog){
            header('Location: index.php?page=listblog');
            exit();
        }
        /*Vérifie si l'article existe */
        $BlogModel = new BlogModel;
        $verif = $BlogModel->selectId_article();;
        if(array_search($id_blog, array_column($verif, 'id_article', $id_blog)) === false){
            header('Location: index.php?page=listblog');
            exit();
        }
        return $id_blog;
    }


    public function CreatearticleMethod(){
        $sess = new SessionController();
        $sess->isLegit();
        $article = new BlogModel();

        if(!empty($_POST)){
            $post = array_map( 'htmlspecialchars', $_POST); /** Sécurise les données */

            $article->createArticle($post['title'], $post['content'], $post['chapo'], $_SESSION['rank']);
            $this->redirect('listblog');
        }elseif(empty($_POST)){
            $view = $this->twig->render('createblog.twig');
            return $view;
        }
    }

  /*  public function SendcommentMethod(){
        if(!empty($_POST){
            $_POST['email']
            $_POST['message']
        }
    }*/




}