<?php

namespace App\Controller;

use App\Controller\Globals\MailController;
use App\Controller\Globals\CookieController;
use App\Controller\Globals\PostController;
use App\Controller\Globals\GetController;
use App\Controller\Globals\SessionController;
use App\Model\AdminModel;
use App\Model\BlogModel;
use App\Model\LoginModel;
use App\Model\UserModel;

/**
 * Class GlobalController
 * @package App\Controller
 */
abstract class ImportController
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
     * @var CookieController
     */
    protected $cookie;

    /**
     * @var MailController
     */
    protected $mail;

    protected $adminSql;

    protected $blogSql;

    protected $loginSql;

    protected $userSql;



    /**
     * GlobalController constructor.
     */
    public function __construct()
    {
        $this->post = new PostController();
        $this->get = new GetController();
        $this->session = new SessionController();
        $this->cookie = new CookieController();
        $this->mail = new MailController();
        $this->adminSql = new AdminModel();
        $this->blogSql = new BlogModel();
        $this->loginSql = new LoginModel();
        $this->userSql = new UserModel();
    }

}