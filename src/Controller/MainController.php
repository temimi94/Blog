<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * @package App\Controller
 */
abstract class MainController extends ImportController
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
        $this->twig->addGlobal("get", $this->get->getGetArray());
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
    public function renderTwigErr(string $twig, string $errormsg = null){

        return $this->twig->render($twig, ['erreur' => $errormsg]);
    }

    /**
     * @param string $twig
     * @param string|null $success
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function renderTwigSuccess(string $twig, string $success = null){

        return $this->twig->render($twig, ['success' => $success]);
    }

    public function render(string $twig, array $param = []){

        return $this->twig->render($twig, $param);
    }


    /**
     * @return mixed
     */
    public function getCurrentLink(){
        $link = filter_input(INPUT_SERVER, 'QUERY_STRING');
        $link = str_replace('page=', '', $link);

        return $link;
    }



}
