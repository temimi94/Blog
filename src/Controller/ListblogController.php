<?php


namespace App\Controller;

use App\Model\ListBlogModel;
use App\Model\MainModel;

class ListBlogController extends  MainController {


    public function DefaultMethod(){
        $ListBlogModel = new ListBlogModel;
        $listBlog = $ListBlogModel->selectAllArticle(); //Afficher du plus récent au plus ancien


        $view = $this->twig->render('listblog.twig', ['listBlog' => $listBlog]);
        return $view;
    }


}