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
        $this->session->verifyAuth();

        $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false,
            'debug' => true
        ));
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal("session", $this->session->getUserArray());
    }

    /**
     * @param string $page
     * @param null $param
     */
    public function redirect(string $page, $param = null)
    {
        header('Location: index.php?page=' . $page . '&' . $param);
        exit;
    }

    /**
     * @param string $twig
     * @param string|null $errormsg
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function redirectTwigErr(string $twig, string $errormsg = null){
        return $this->twig->render($twig, ['error' => $errormsg]);
    }


    /**
     * @return mixed
     */
    public function getCurrentLink(){
        $link = filter_input(INPUT_SERVER, 'REQUEST_URI');
        return $link;
    }



}
