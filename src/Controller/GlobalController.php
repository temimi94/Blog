<?php

namespace App\Controller;

use App\Controller\Globals\PostController;
use App\Controller\Globals\GetController;
use App\Controller\Globals\SessionController;

abstract class GlobalController
{

    protected $post;

    protected $get;

    protected $session;

    public function __construct()
    {
        $this->post = new PostController();
        $this->get = new GetController();
        $this->session = new SessionController();
    }

}