<?php

namespace App;

class Router{

    public function __construct(){
        $this->parseUrl();
        $this->setController();
        $this->controllerSendView();
    }
    public function parseUrl(){
        $page = filter_input(INPUT_GET, 'page');
        if(!isset($page)){
            $page = 'home';
        }
        $this->controller = $page;
    }

    public function setController(){
        $this->controller = ucfirst(strtolower($this->controller));
        $this->controller = 'App\Controller\\' . $this->controller . 'Controller';
        //var_dump($this->controller);
        if(!class_exists($this->controller)){
            $this->controller = 'App\Controller\HomeController';
        }
        //var_dump($this->controller);

    }

    public function controllerSendView(){
        $view = new $this->controller();
    }
}