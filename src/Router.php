<?php

namespace App;

class Router{

    const DEFAULT_METHOD = 'DefaultMethod';

    public function __construct(){
        $this->parseUrl();
        $this->setController();
        $this->setMethod();
        $this->controllerSendView();
    }
    public function parseUrl(){
        $page = filter_input(INPUT_GET, 'page');
        if(!isset($page)){
            $page = 'home';
        }
        $this->controller = $page;

        $method = filter_input(INPUT_GET, 'method');
        if(!isset($method)){
            $method = 'DefaultMethod';
        }
        $this->method = $method;
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

    public function setMethod(){
        $this->method = ucfirst(strtolower($this->method)) . 'Method';

        if(!method_exists($this->controller, $this->method)){
         $this->method = self::DEFAULT_METHOD;
        }
    }

    public function controllerSendView(){
        $this->controller = new $this->controller;
        $reponse = call_user_func([$this->controller, $this->method]);

        echo filter_var($reponse);

    }
}