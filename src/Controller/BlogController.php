<?php

namespace App\Controller;

use App\Model\BlogModel;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends MainController
{


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        $id_blog = self::getId();

        $BlogModel = new BlogModel;
        $blog = $BlogModel->selectArticle($id_blog);
        $comment = $BlogModel->selectCommentByArticle($id_blog);

        return $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment]);
    }

    public function listMethod()
    {
        $blog = new BlogModel;
        $blog = $blog->selectAllArticle(); //TODO Afficher seulement 10 Articles maximum

        return $this->twig->render('listblog.twig', ['listBlog' => $blog]);
    }

    /**
     * @return mixed
     */
    private function getId()
    {
        /* Filtre si c'est un entier */
        $id_blog = $this->get->getGetVar('idblog');

        /*Redirection si la variable est vide*/
        if (!$id_blog) {
            $this->redirect('list');
        }
        /*Vérifie si l'article existe */
        $BlogModel = new BlogModel;
        $verif = $BlogModel->selectId_article();;
        if (array_search($id_blog, array_column($verif, 'id_article', $id_blog)) === false) {
            $this->redirect('list');
        } // Create function ifArticleExist
        return $id_blog;
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createArticleMethod()
    {
        $admin = new AdminController();
        $admin->isLegitAdmin();

        $article = new BlogModel();
        $post = $this->post->getPostArray();

        if (!empty($post)) {
            $article->createArticle($post['title'], $post['content'], $post['chapo'], $this->session->getUserVar('id_user'));
            $this->redirect('list');
        } elseif (empty($post)) {
            return $this->twig->render('createblog.twig');
        }
    }


    public function createCommentMethod()
    {
        $post = $this->post->getPostArray();
        if(empty($post['comment'])) return $this->redirect('list'); //TODO Vérifier si le contenu de $post['content'] possède au moins 5 caractère
        $id_blog = self::getId();
        if($this->session->isLogged() == false) $this->redirect('home');

        if (!empty($post)) {
            $comment = new BlogModel();
            $comment->createComment($id_blog, $post['comment'], $this->session->getUserVar('id_user'));
            $this->redirect('blog&idblog=' . $id_blog);
        } elseif (empty($post)) {
            $this->redirect('blog&idblog=' . $id_blog);
        }
    }


}