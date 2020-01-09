<?php


namespace App\Controller;

use App\Model\ListBlogModel;
use App\Model\MainModel;

class ListController extends MainController
{


    public function defaultMethod()
    {
        $ListBlogModel = new ListBlogModel;
        $listBlog = $ListBlogModel->selectAllArticle(); //Afficher du plus récent au plus ancien

        return $this->twig->render('listblog.twig', ['listBlog' => $listBlog]);
    }


}