<?php

namespace App\Controller;

use App\Model\BlogModel;
use App\Model\MainModel;

class BlogController extends MainController {



    public function __construct(){
        parent::__construct();
        $id_blog = $this->getId();
        $blog = BlogModel::selectArticle($id_blog);
        $author = MainModel::selectAuthorById($blog['author_id']);
        $comment = listComment($id_blog);
        $view = $this->twig->render('blog.twig', ['blog' => $blog, 'author' => $author, 'comment' => $comment]);
        echo filter_var($view);
    }

    private function getId(){
        /* Filtre si c'est un entier */
        $id_blog = filter_input(INPUT_GET, 'idblog', FILTER_VALIDATE_INT);

        /*Redirection si la variable est vide où si l'id de l'article n'existe pas */
        if(empty($id_blog)){
            header('Location: index.php?page=listblog');
            exit();
        }
        /*Vérifie si l'article existe */
        $verif = BlogModel::selectId_article();;
        if(in_array($id_blog, $verif)){
            header('Location: index.php?page=listblog');
            exit();
        }
        return $id_blog;
    }

    private function listComment($id_blog){

        return BlogModel::selectCommentByArticle($id_blog);
    }


}