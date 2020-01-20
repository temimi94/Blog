<?php

namespace App\Controller;

use App\Controller\Globals\CookieController;
use App\Controller\Globals\PostController;
use App\Controller\Globals\GetController;
use App\Controller\Globals\SessionController;

/**
 * Class GlobalController
 * @package App\Controller
 */
abstract class GlobalController
{

    /**
     * @var PostController
     */
    protected $post;

    /**
     * @var GetController
     */
    protected $get;

    /**
     * @var SessionController
     */
    protected $session;

    /**
     * GlobalController constructor.
     */

    protected $cookie;

    /**
     * GlobalController constructor.
     */
    public function __construct()
    {
        $this->post = new PostController();
        $this->get = new GetController();
        $this->session = new SessionController();
        $this->cookie = new CookieController();
    }

}