<?php

use App\Router;

use Tracy\Debugger;

require_once '../vendor/autoload.php';


Debugger::enable();




new Router();
