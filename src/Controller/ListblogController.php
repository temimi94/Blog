<?php


namespace App\Controller;

use App\Model\ListBlogModel;
use App\Model\MainModel;

class ListBlogController extends  MainController {


    public function DefaultMethod(){
        $ListBlogModel = new ListBlogModel;
        $listBlog = $ListBlogModel->selectAllArticle();

        $view = new MainController;
        $view = $view->twig->render('listblog.twig', ['listBlog' => $listBlog]);
        return $view;
    }


}