<?php

namespace App\Controller;



use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController extends MainController {

    public function __construct(){
        parent::__construct();
        $view = $this->twig->render('home.twig');
        echo filter_var($view);
    }



}