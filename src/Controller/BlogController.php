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

    private function getId(){
        /* Filtre si c'est un entier */
        $id_blog = $this->get->getGetVar('idblog');

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
        } // Create function ifArticleExist
        return $id_blog;
    }


    public function CreatearticleMethod(){
        $sess = new SessionController();
        $sess->isLegit();
        $article = new BlogModel();
        $post = $this->post->getPostArray();

        if(!empty($post)){
            $article->createArticle($post['title'], $post['content'], $post['chapo'], $_SESSION['id_user']);
            $this->redirect('listblog');
        }elseif(empty($post)){
            $view = $this->twig->render('createblog.twig');
            return $view;
        }
    }

    public function CreatecommentMethod(){
        $post = $this->post->getPostArray();
        $id_blog = self::getId();
        $sess = new SessionController();
        $sess->isLegit();

        if(!empty($post)){
            $comment = new BlogModel();
            $comment->createComment($id_blog, $post['comment'], $_SESSION['id_user']);
            $this->redirect('blog&idblog='.$id_blog);
        }elseif(empty($post)){
            $this->redirect('blog&idblog=' .$id_blog);
        }
    }




}