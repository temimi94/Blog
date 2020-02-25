<?php

namespace App;

class Router
{

    const DEFAULT_METHOD = 'DefaultMethod';

    public function __construct()
    {
        $this->getUrl();
        $this->setController();
        $this->setMethod();
        $this->getControllerView();
    }

    public function getUrl()
    {
        $page = filter_input(INPUT_GET, 'page');
        if (!isset($page)) {
            $page = 'home';
        }
        $this->controller = $page;

        $method = filter_input(INPUT_GET, 'method');
        if (!isset($method)) {
            $method = 'DefaultMethod';
        }
        $this->method = $method;
    }

    public function setController()
    {
        $this->controller = ucfirst(strtolower($this->controller));
        $this->controller = 'App\Controller\\' . $this->controller . 'Controller';
        if (!class_exists($this->controller)) {
            $this->controller = 'App\Controller\HomeController';
        }

    }

    public function setMethod()
    {
        $this->method = ucfirst($this->method) . 'Method';

        if (!method_exists($this->controller, $this->method)) {
            $this->method = self::DEFAULT_METHOD;
        }
    }

    public function getControllerView()
    {
        $this->controller = new $this->controller;
        $reponse = call_user_func([$this->controller, $this->method]);

        echo filter_var($reponse);

    }
}