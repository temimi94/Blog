<?php

namespace App\Controller;


class HomeController extends MainController {

    public function DefaultMethod(){
        $main = new MainController;
        return $main->twig->render('home.twig');
        //echo filter_var($view);
    }



}