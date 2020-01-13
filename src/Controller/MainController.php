<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * @package App\Controller
 */
class MainController extends GlobalController
{
    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     * Add session var to twig
     */
    public function __construct()
    {
        parent::__construct();

        $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false,
            'debug' => true
        ));
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal("session", $this->session->getUserArray());
    }

    /**
     * @param string $page
     */
    public function redirect(string $page)
    {
        header('Location: index.php?page=' . $page);
        exit;
    }


    /**
     * @return mixed
     */
    public function getCurrentLink(){
        return $_SERVER['REQUEST_URI'];
    }

}
