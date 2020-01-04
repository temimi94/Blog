<?php

use App\Router;

use Tracy\Debugger;

require_once '../vendor/autoload.php';

session_start();

Debugger::enable();




new Router();
