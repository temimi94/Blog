<?php

namespace App\Controller;

use App\Controller\Globals\CookieController;
use App\Controller\Globals\PostController;
use App\Controller\Globals\GetController;
use App\Controller\Globals\SessionController;

use App\Model\AdminModel;
use App\Model\BlogModel;
use App\Model\LoginModel;
use App\Model\UserModel;

/**
 * Class ImportController
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

    /**
     * @var AdminModel
     */
    protected $adminSql;

    /**
     * @var BlogModel
     */
    protected $articleSql;

    /**
     * @var LoginModel
     */
    protected $loginSql;

    /**
     * @var UserModel
     */
    protected $userSql;

    /**
     * ImportController constructor.
     */
    public function __construct()
    {
        $this->post = new PostController();
        $this->get = new GetController();
        $this->session = new SessionController();
        $this->cookie = new CookieController();
        $this->mail = new MailController();
        $this->adminSql = new AdminModel();
        $this->articleSql = new BlogModel();
        $this->loginSql = new LoginModel();
        $this->userSql = new UserModel();
    }

}