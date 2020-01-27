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

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function listMethod()
    {
        $blog = $this->blogSql->selectAllArticle();
        //$blog = $blog->selectAllArticle(); //TODO Afficher seulement 10 Articles maximum

        return $this->twig->render('listblog.twig', ['listBlog' => $blog]);
    }

    /**
     * @return mixed
     */
    private function getId()
    {
        /* Filtre si c'est un entier */
        $idBlog = $this->get->getGetVar('idblog');

        /*Redirection si la variable est vide*/
        if (!$idBlog) {
            $this->redirect('home');
        }
        /*Vérifie si l'article existe */
        $BlogModel = new BlogModel;
        $verif = $this->blogSql->selectIdArticle();;
        if (array_search($idBlog, array_column($verif, 'id_article', $idBlog)) === false) {
            $this->redirect('list');
        } // Create function ifArticleExist
        return $idBlog;
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createArticleMethod() //TODO Déplacer
    {
        $admin = new AdminController();
        $admin->isLegitAdmin();

        $post = $this->post->getPostArray();

        if (!empty($post)) {
            $verif = $this->post->verifyPost();
            if($verif !== true) return $this->twig->render('createblog.twig', ['erreur' => $verif]);
            $this->blogSql->createArticle($post['title'], $post['content'], $post['chapo'], $this->session->getUserVar('id_user'));
            return $this->twig->render('createblog.twig', ['success' => 'Votre article nous a bien été envoyé! Il ne manque plus qu\'à le valider!']);
        } elseif(empty($post)) {
            return $this->twig->render('createblog.twig');
        }
        //$this->redirect('blog&method=createArticle');
    }


    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createCommentMethod()
    {
        if($this->session->isLogged() == false) $this->redirect('home');

        $post = $this->post->getPostArray();
        $verif = $this->post->verifyPost();
        $idBlog = self::getId();

        $blog = $this->blogSql->selectArticle($idBlog);
        $comment = $this->blogSql->selectCommentByArticle($idBlog);

        if($verif !== true ) return $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment, 'erreur' => $verif]);
        if (!empty($post)) {
            $this->blogSql->createComment($idBlog, $post['comment'], $this->session->getUserVar('id_user'));
            return $this->twig->render('blog.twig', ['blog' => $blog, 'comment' => $comment, 'success' => 'Votre commentaire a bien été envoyé.']);
        } elseif (empty($post)) {
            $this->redirect('blog&idblog=' . $idBlog);
        }
    }


}