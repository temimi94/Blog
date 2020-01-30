<?php

namespace App\Controller;


/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends MainController
{
    /**
     *
     */
    const TWIG = 'blog.twig';

    /**
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function defaultMethod()
    {
        $id_blog = self::getId();

        $blog = $this->blogSql->selectArticle($id_blog);
        $comment = $this->blogSql->selectCommentByArticle($id_blog);

        return $this->twig->render(self::TWIG, ['blog' => $blog, 'comment' => $comment]);
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
    public function createCommentMethod()
    {
        if($this->session->isLogged() == false) {
            $this->redirect('home');
        }

        $post = $this->post->getPostArray();
        $verif = $this->post->verifyPost();
        $idBlog = self::getId();

        $blog = $this->blogSql->selectArticle($idBlog);
        $comment = $this->blogSql->selectCommentByArticle($idBlog);

        if($verif !== true ){

            return $this->twig->render(self::TWIG, ['blog' => $blog, 'comment' => $comment, 'erreur' => $verif]);
        }
        if (!empty($post)) {
            $this->blogSql->createComment($idBlog, $post['comment'], $this->session->getUserVar('id_user'));

            return $this->twig->render(self::TWIG, ['blog' => $blog, 'comment' => $comment, 'success' => 'Votre commentaire a bien été envoyé.']);
        } elseif (empty($post)) {

            $this->redirect('blog&idblog=' . $idBlog);
        }
    }


}