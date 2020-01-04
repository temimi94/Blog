<?php
namespace App\Controller;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class MainController extends GlobalController
{
    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
    {
        parent::__construct();



        $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false,
            'debug' => true
        ));
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal("session", $this->session->getUserArray()); /**Ajoute la variable $_SESSION a Twig **/
    }

    public function redirect(string $page){
        header('Location: index.php?page='.$page);
        exit;
    }

    public function err404(){
        header('HTTP/1.0 404 Not Found');
        exit;
    }

    public function isLogged()
    {
        if (!empty($this->session->getUserVar('session_id'))) {
            return true;
        }
        return false;

    }
}
