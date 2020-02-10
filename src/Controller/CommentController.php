<?php

namespace App\Controller;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Class CommentController
 * @package App\Controller
 */
class CommentController extends MainController
{


    /* All logged users */
    /**
     * @return string
     */
    public function createCommentMethod()
    {
        if ($this->session->isLogged() == false) {
            $this->redirect('home');
        }

        $post = $this->post->getPostArray();
        $verify = $this->post->verifyPost();

        $idArticle = $this->get->getGetVar('idArticle');

        $article = $this->articleSql->selectArticle($idArticle);
        $comment = $this->articleSql->selectCommentByArticle($idArticle);

        if ($verify !== true) {
            $array = array('article' => $article,
                'comment' => $comment,
                'erreur' => $verify);

            return $this->render('article.twig', $array);

        }
        if (!empty($post)) {
            $this->articleSql->createComment($idArticle, $post['comment'], $this->session->getUserVar('idUser'));

            $array = array('article' => $article,
                'comment' => $comment,
                'success' => 'Votre commentaire a bien été envoyé');

            return $this->render('article.twig', $array);

        }
    }

    /* Admin panel */
    /**
     * @return string
     */
    public function listAllCommentMethod()
    {
        $this->session->isAdmin();


        $req = $this->adminSql->getAllComment();

        return $this->render('admin/admin.twig', ['comment' => $req]);
    }

    /* Admin panel */
    /**
     *
     */
    public function approveCommentMethod()
    {
        $this->session->isAdmin();

        $get = $this->get->getGetVar('idComment');
        if ($get === false) {
            $this->redirect('admin');
        }

        $this->adminSql->approveComment($get);
        $this->redirect('comment&method=listAllComment');
    }

    /* Admin panel */
    /**
     *
     */
    public function deleteCommentMethod()
    {
        $this->session->isAdmin();

        $get = $this->get->getGetVar('idComment');
        if ($get === false) {
            $this->redirect('admin');
        }

        $this->adminSql->deleteComment($get);
        $this->redirect('comment&method=listAllComment');
    }


    /* User panel */
    /**
     * @return string
     */
    public function listMyCommentMethod()
    {
        $this->session->isUser();

        $req = $this->userSql->getUserComment($this->session->getUserVar('idUser'));

        return $this->render('user/user.twig', ['comments' => $req]);
    }


    /* User panel */
    /**
     *
     */
    public function deleteMyCommentMethod()
    {
        $this->session->isUser();

        $get = $this->get->getGetVar('idComment');

        if ($get === false) {
            $this->redirect('user');
        }

        $this->userSql->deleteComment($get);

        $this->redirect('comment&method=listMyComment');
    }


}