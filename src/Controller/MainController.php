<?php
namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MainController
{

    protected $twig = null;

    /* Charge le chemin des Vues */

    public function __construct()
    {
        return $this->twig = new Environment(new FilesystemLoader('../src/View'), array(
            'cache' => false
        ));

    }
}