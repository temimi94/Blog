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
            $this->redirect('home');
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
            $verif = $this->post->verifyPost();
            if($verif !== true) return $this->twig->render('createblog.twig', ['erreur' => $verif]);
            $article->createArticle($post['title'], $post['content'], $post['chapo'], $this->session->getUserVar('id_user'));
            return $this->twig->render('createblog.twig', ['success' => 'Votre article nous a bien été envoyé! Il ne manque plus qu\'à le valider!']);
        } elseif(empty($post)) {
            return $this->twig->render('createblog.twig');
        }
        //$this->redirect('blog&method=createArticle');
    }


    public function createCommentMethod()
    {
        if($this->session->isLogged() == false) $this->redirect('home');

        $post = $this->post->getPostArray();
        $verif = $this->post->verifyPost();
        $id_blog = self::getId();

        $BlogModel = new BlogModel; /* Gérer la redirection */
        $blog = $BlogModel->selectArticle($id_blog);
        $comment = $BlogModel->selectCommentByArticle($id_blog);

        if($verif !== true ) return $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment, 'erreur' => $verif]);
        if (!empty($post)) {
            $BlogModel->createComment($id_blog, $post['comment'], $this->session->getUserVar('id_user'));
            return $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment, 'success' => 'Votre commentaire a bien été envoyé.']);
        } elseif (empty($post)) {
            $this->redirect('blog&idblog=' . $id_blog);
        }
    }


}