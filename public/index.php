<?php

use App\Router;

use Tracy\Debugger;

require_once '../vendor/autoload.php';

Debugger::enable();

new Router();
/**
$req = new \App\Model\BlogModel();
echo '<pre>';
var_dump($req->selectArticle(3));
echo '</pre>';
 */