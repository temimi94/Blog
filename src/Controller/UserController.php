<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Model\BlogModel;
use App\Model\ListBlogModel;
use App\Model\MainModel;

class UserController extends MainController
{

    public function defaultMethod()
    {
        $this->isLegitUser();
        return $this->twig->render('user.twig');
    }


    public function listCommentMethod()
    {
        $this->isLegitUser();

        $req = new UserModel();
        $req = $req->getUserComment($this->session->getUserVar('id_user'));

        return $this->twig->render('user.twig', ['comment' => $req]);
    }


    public function deleteCommentMethod()
    {
        $this->isLegitUser();

        $get = $this->get->getGetVar('idcomment');
        if ($get === false) $this->redirect('user');

        $req = new UserModel();
        $req->deleteComment($get);
        $this->redirect('user&method=listcomment');
    }


    public function isLegitUser()
    {
        if ($this->session->getUserVar('rank') != 'Utilisateur') $this->redirect('home');
    }

}