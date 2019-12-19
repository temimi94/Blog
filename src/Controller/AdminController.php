<?php

namespace App\Controller;

class AdminController extends MainController
{
    public function DefaultMethod(){
        $sess = new SessionController();
        $sess->isLegit();
        $main = new MainController;
        var_dump($_SESSION);
        return $main->twig->render('admin.twig');

    }

}