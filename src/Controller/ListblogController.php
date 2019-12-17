<?php


namespace App\Controller;

use App\Model\ListBlogModel;
use App\Model\MainModel;

class ListBlogController extends  MainController {

    private $comp = 0;

    public function __construct(){
        parent::__construct();
        $listBlog = ListBlogModel::selectAllArticleWithAuthor();
        $view = $this->twig->render('listblog.twig', ['listBlog' => $listBlog]);
        echo filter_var($view);
    }


}