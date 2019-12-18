<?php


namespace App\Controller;

use App\Model\ListBlogModel;
use App\Model\MainModel;

class ListBlogController extends  MainController {

    private $comp = 0;

    public function DefaultMethod(){
        $view = new MainController;
        $listBlog = ListBlogModel::selectAllArticleWithAuthor();
        $view = $view->twig->render('listblog.twig', ['listBlog' => $listBlog]);
        return $view;
    }


}