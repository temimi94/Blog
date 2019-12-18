<?php

namespace App\Controller;

use App\Model\BlogModel;
use App\Model\MainModel;

class BlogController extends MainController {



    public static function DefaultMethod(){
        $view = new MainController;
        $id_blog = self::getId();
        $blog = BlogModel::selectArticle($id_blog);
        $comment = self::listComment($id_blog);
        $view = $view->twig->render('blog.twig', ['blog' => $blog, 'author' => $author, 'comment' => $comment]);
        return $view;
    }

    private static function getId(){
        /* Filtre si c'est un entier */
        $id_blog = filter_input(INPUT_GET, 'idblog', FILTER_VALIDATE_INT);

        /*Redirection si la variable est vide où si l'id de l'article n'existe pas */
        if(empty($id_blog)){
            header('Location: index.php?page=listblog');
            exit();
        }
        /*Vérifie si l'article existe */
        $verif = BlogModel::selectId_article();;
        if(array_search($id_blog, array_column($verif, 'id_article', $id_blog)) === false){
            header('Location: index.php?page=listblog');
            exit();
        }
        return $id_blog;
    }

    private static function listComment($id_blog){

        return BlogModel::selectCommentByArticle($id_blog);
    }


}