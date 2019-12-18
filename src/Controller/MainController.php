<?php
namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MainController
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
        $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false,
            'debug' => true
        ));
    }
}
